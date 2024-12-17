<?php

namespace App\Http\Controllers\Api\v1\Event;

use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Enums\ActivityEventEnum;
use App\Events\ActivityEvent;
use App\Http\Controllers\Api\v1\ApiBaseController;
use App\Http\Requests\Api\v1\Event\StoreRequest;
use App\Http\Requests\Api\v1\Event\UpdateEventRequest;
use App\Http\Requests\Api\v1\Topic\ChangeOrderRequest;
use App\Http\Resources\Api\v1\Event\EventProgressCollection;
use App\Http\Resources\Api\v1\Event\EventResource;
use App\Http\Resources\Api\v1\Event\Settings\CourseSettingsResource;
use App\Model\Certificate;
use App\Model\Event;
use App\Model\Topic;
use App\Model\User;
use App\Services\v1\EventDuplicationService;
use App\Services\v1\EventService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use ZipArchive;

class EventController extends ApiBaseController
{
    public function __construct(
        private readonly EventDuplicationService $eventDuplicationService,
        private readonly EventService $service
    ) {
    }

    /**
     * Display a listing of the events.
     */
    public function index(Request $request): JsonResponse
    {
        $query = $this->applyRequestParametersToQuery(Event::query(), $request);

        $userId = $request->user_id;
        $notUserId = $request->not_user_id;
        $query = $query->when($userId, function ($q) use ($userId) {
            $q->whereHas('users', function ($q) use ($userId) {
                $q->where('users.id', $userId);
            });
        })->when($notUserId, function ($q) use ($notUserId) {
            $q->whereDoesntHave('users', function ($q) use ($notUserId) {
                $q->where('users.id', (int) $notUserId);
            });
        });

        return new JsonResponse(
            $this->paginateByRequestParameters($query, $request)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): EventResource
    {
        $this->authorize('create', Event::class);

        return new EventResource($this->service->store($request->validated()));
    }

    /**
     * Display the event.
     */
    public function show(Request $request, Event $event, IEventSettingsService $settingsService): JsonResponse
    {
        $event = $this->applyRequestParametersToModel($event, $request);
        $event->settings = CourseSettingsResource::make($settingsService->getEventSettings($event));

        return new JsonResponse($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event, IEventSettingsService $settingsService): EventResource
    {
        $this->authorize('update', $event);

        $event = $this->service->update($event, $request->validated());

        $settingsService->updateSettings($event, $request->toDto());

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        event(new ActivityEvent(Auth::user(), ActivityEventEnum::CourseAdded->value, $event->title, Auth::user(), $event));

        $event->delete();

        return \response()->json(['success' => true]);
    }

    public function certificatePreview(string $template)
    {
        $templates = [
            'certificate',
            'certificate_facebook_marketing',
            'kc_attendance',
            'kc_attendance_2022a',
            'kc_attendance_2022b',
            'kc_deree_attendance',
            'kc_deree_attendance_2022',
            'kc_deree_diploma',
            'kc_deree_diploma_2022',
            'kc_diploma',
            'kc_diploma_2022a',
            'kc_diploma_2022b',
            'new_kc_certificate',
            'new_kc_deree_certificate',
        ];

        $certificate = [
            'success'             => true,
            'meta_title'          => '#META TITLE#',
            'firstname'           => '#FIRSTNAME#',
            'lastname'            => '#LASTNAME#',
            'certification_title' => '#CERTIFICATION TITLE#',
            'certification_date'  => '#DATE#',
            'expiration_date'     => '#EXP. DATE#',
            'credential'          => '#CREDENTIAL#',
        ];

        if (in_array($template, $templates)) {
            return view("admin.certificates.{$template}", compact('certificate'));
        }

        throw new NotFoundHttpException('Certificate template not found');
    }

    /**
     * @throws Throwable
     */
    public function checkSlugAvailability(Event $event, Request $request): JsonResponse
    {
        $inUse = Event::query()->whereHas('slugable', function ($query) use ($event, $request) {
            $query->where('slug', $request->input('slug'));
            $query->where('slugable_id', '!=', $event->id);
        })->exists();

        if ($inUse) {
            return response()->json(['message' => 'This slug is already in use', 'usage' => 'used_for_another_course'], 409);
        }

        $slug = $event->slugable()->first();

        if ($slug->slug == $request->input('slug')) {
            return response()->json(['message' => 'This slug is already in use for this course', 'usage' => 'used_for_this_course']);
        }

        return response()->json(['message' => 'This slug is available for using', 'usage' => 'available_for_using']);
    }

    /**
     * @throws Throwable
     */
    public function duplicateEvent(Event $event): Response
    {
        $status = $this->eventDuplicationService->duplicate($event) ? Response::HTTP_CREATED : Response::HTTP_INTERNAL_SERVER_ERROR;

        return response('Duplication event attempt', $status);
    }

    public function userSubscriptions(User $user, Request $request): JsonResponse
    {
        $events = $user->subscriptionEvents()
            ->with('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy($request->order_by ?? 'id', $request->order_type ?? 'desc')
            ->paginate($request->per_page ?? 5);

        return response()->json($events);
    }

    public function getEventProgress(User $user, Request $request): AnonymousResourceCollection
    {
        $events = $user->events_for_user_list1()
            ->with('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->orderBy($request->order_by ?? 'id', $request->order_type ?? 'desc')
            ->paginate($request->per_page ?? 5);

        return EventProgressCollection::collection($events);
    }

    public function changeTopicOrder(Event $event, Topic $topic, ChangeOrderRequest $request): JsonResponse
    {
        return \response()->json(
            ['success' => $this->service->changePriority($event, $topic, $request->order)],
            Response::HTTP_OK);
    }

    public function exportCertificates(Event $event)
    {
        $users = $event->users;
        $zip = new ZipArchive();

        $fileName = 'certificates.zip';
        File::deleteDirectory(public_path('certificates_folders'));
        File::makeDirectory(public_path('certificates_folders'));
        if (File::exists(public_path($fileName))) {
            unlink(public_path($fileName));
        }

        $successMessage = (isset($event->event_info()['certificate']['has_certificate_exam']) && $event->event_info()['certificate']['has_certificate_exam'] && isset($event->event_info()['certificate']['messages']['success'])) ? $event->event_info()['certificate']['messages']['success'] : $event->title;
        $failureMessage = isset($event->event_info()['certificate']['messages']['completion']) ? strip_tags($event->event_info()['certificate']['messages']['completion']) : '';
        $certificateEventTitle = $event->title;

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === true) {
            foreach ($users as $user) {
                if ($user->instructor->first()) {
                    continue;
                }

                $template = 'new_kc_certificate';
                $template_failed = 'new_kc_certificate';

                if (!($cert = $event->userHasCertificate($user->id)->first())) {
                    $cert = new Certificate;
                    $cert->success = true;
                    $cert->firstname = $user->firstname;
                    $cert->lastname = $user->lastname;
                    $cert->certificate_title = $successMessage;
                    $cert->credential = get_certifation_crendetial();
                    $createDate = strtotime(date('Y-m-d'));
                    $cert->create_date = $createDate;
                    $cert->expiration_date = strtotime(date('Y-m-d', strtotime('+24 months', strtotime(date('Y-m-d')))));
                    $cert->certification_date = date('F') . ' ' . date('Y');
                    $cert->template = $template;
                    $cert->save();

                    $cert->event()->save($event);
                    $cert->user()->save($user);
                } else {
                    $cert->certificate_title = $cert->success ? $successMessage : $failureMessage;
                    $cert->template = $cert->success ? $template : $template_failed;
                    $cert->save();
                }

                $certificate['firstname'] = $cert->firstname;
                $certificate['lastname'] = $cert->lastname;
                $certificate['certification_date'] = $cert->certification_date;
                $certificate['expiration_date'] = $cert->expiration_date ? date('F Y', $cert->expiration_date) : null;
                $certificate['certification_title'] = $cert->certificate_title;
                $certificate['credential'] = $cert->credential;
                $certificate['certificate_event_title'] = $certificateEventTitle;

                $certificate['meta_title'] = strip_tags($cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id); //$cert->lastname . ' ' . $cert->firstname . ' ' . $cert->certificate_title . ' ' . $cert->user()->first()->kc_id;

                $contxt = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed'=> true,
                    ],
                ]);

                $pdf = PDF::setOptions([
                    'isHtml5ParserEnabled'=> true,
                    'isRemoteEnabled' => true,

                ]);

                $name = $user->lastname . '_' . $user->firstname . '_' . trim(preg_replace('/\s\s+/', '', strip_tags($cert->certificate_title))) . '_' . $user->kc_id;
                $fn = $name . '.pdf';
                $fn = htmlspecialchars_decode($fn, ENT_QUOTES);

                $pdf->getDomPDF()->setHttpContext($contxt);
                $pdf->loadView('admin.certificates.' . $cert->template, compact('certificate'))->setPaper('a4', 'landscape')->save(public_path('certificates_folders/' . $fn))->stream($fn);

                $zip->addFile(public_path('certificates_folders/' . $fn), $fn);
            }
        }

        $zip->close();
        File::deleteDirectory(public_path('certificates_folders'));

        return response()->download(public_path($fileName));
    }
}
