<?php

namespace App\Services\Event;

use App\Audience;
use App\Contracts\Api\v1\Event\IEventSettingsService;
use App\Dto\Api\v1\Event\Participants\SettingsDto;
use App\Model\Career;
use App\Model\Delivery;
use App\Model\DynamicAds;
use App\Model\Event;
use App\Model\Exam;
use App\Model\Language;
use App\Model\Partner;
use App\Model\PaymentMethod;
use App\Model\PaymentOption;
use App\Model\Skill;
use App\Model\Tag;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EventSettingsService implements IEventSettingsService
{
    public function __construct(private EventFileService $eventFileService)
    {
    }

    public function getEventSettings(Event $event): array
    {
        return [
            'absences' => $this->prepareEventAbsencesSettings($event),
            'access' => $this->prepareEventAccessesSettings($event),
            'audience' => $this->prepareEventAudienceSettings($event),
            'administration' => $this->prepareEventAdministrationSettings($event),
            'career_path_and_skills' => $this->prepareEventCareerPathAnsSkillsSettings($event),
            'certification' => $this->prepareEventCertificationSettings($event),
            'delivery' => $this->prepareEventDeliverySettings($event),
            'discount_coupons' => $this->prepareEventDiscountCouponsSettings($event),
            'dynamic_advertising' => $this->prepareEventDynamicAdvertisingSettings($event),
            'e_learning_bonus' => $this->prepareEventBonusSettings($event),
            'files' => $this->prepareEventFilesSettings($event),
            'language' => $this->prepareEventLanguageSettings($event),
            'partnerships' => $this->prepareEventPartnershipSettings($event),
            'payments' => $this->prepareEventPaymentsSettings($event),
            'exams' => $this->prepareEventExamsSettings($event),
            'search_engine_optimisation' => $this->prepareEventSearchSettings($event),
            'support_group' => $this->prepareEventSupportGroupSettings($event),
            'surveys' => $this->prepareEventSurveySettings($event),
            'call_to_action_buttons' => $this->prepareEventCTASettings($event),
            'related_courses' => $this->prepareEventRelatedCourseSettings($event),
            'tags' => $this->prepareEventTagsSettings($event),
        ];
    }

    public function updateSettings(Event $event, SettingsDto $settingsDto): void
    {
        $data = $settingsDto->getData();

        $this->updateEventData($event, $data['event'] ?? []);
        $this->updateEventInfoData($event, $data['eventInfo'] ?? []);
        $this->updateEventDeliveryData($event, $data['eventDelivery'] ?? []);
        $this->updateEventDynamicAdsData($event, $data['dynamicAds'] ?? []);
        $this->updateEventBonusCoursesData($event, $data['bonus_courses'] ?? []);
        $this->updateEventMetaData($event, $data['metable'] ?? []);
        $this->updateEventSkillsData($event, $data['skills'] ?? null);
        $this->updateEventPathsData($event, $data['paths'] ?? null);
        $this->updateEventCityData($event, $data['city'] ?? null);
        $this->updateEventPartnersData($event, $data['partner'] ?? null);
        $this->updateEventPaymentGatewaysData($event, $data['paymentMethod'] ?? null);
        $this->updateEventPaymentOptionsData($event, $data['paymentOptions'] ?? null);
        $this->updateEventExamData($event, $data['exam'] ?? null);
        $this->updateEventFilesData($event, $data['files'] ?? null);
        $this->updateEventAudiencesData($event, $data['audiences'] ?? null);
        $this->updateEventRelatedCoursesData($event, $data['related_courses'] ?? null);
        $this->updateEventTagsData($event, $data['tags'] ?? null);
    }

    private function updateEventData(Event $event, array $data): void
    {
        if ($data) {
            $event->update($data);

            if (isset($data['slug'])) {
                $event->slugable()->update(['slug' => $data['slug']]);
            }
        }
    }

    private function updateEventInfoData(Event $event, array $data): void
    {
        if ($data) {
            $event->eventInfo()->update($data);
        }
    }

    private function updateEventDeliveryData(Event $event, array $data): void
    {
        $event->delivery()->sync($data);
    }

    private function updateEventDynamicAdsData(Event $event, array $data): void
    {
        if ($data) {
            $event->dynamicAds()->exists()
                ? $event->dynamicAds()->update($data)
                : $event->dynamicAds()->create($data);
        }
    }

    private function updateEventBonusCoursesData(Event $event, array $data): void
    {
        if ($data) {
            $syncData = [];

            if (isset($data['offer_bonus_course']) && $data['offer_bonus_course']) {
                foreach ($data['selected_courses'] as $selected) {
                    $syncData[$selected] = [
                        'access_period' => $data['access_period'] ?? null,
                        'exams_required' => $data['exams_required'] ?? false,
                    ];
                }
            }

            $event->bonusCourse()->sync($syncData);
        }
    }

    private function updateEventMetaData(Event $event, array $data): void
    {
        if ($data) {
            $event->metable()->exists()
                ? $event->metable()->update($data)
                : $event->metable()->create($data);
        }
    }

    private function updateEventSkillsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->skills()->sync($data);
        }
    }

    private function updateEventPathsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->career()->sync($data);
        }
    }

    private function updateEventCityData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->city()->sync($data);
        }
    }

    private function updateEventPartnersData(Event $event, ?int $data = null): void
    {
        $event->partners()->sync($data ? Arr::wrap($data) : []);
    }

    private function updateEventPaymentGatewaysData(Event $event, ?int $data = null): void
    {
        if ($data) {
            $event->paymentMethod()->sync(Arr::wrap($data));
        }
    }

    private function updateEventPaymentOptionsData(Event $event, ?array $data = null): void
    {
        if ($data) {
            $syncData = [];

            foreach ($data as $option) {
                if (isset($option['id']) && ($option['active'] ?? false)) {
                    $syncData[$option['id']] = [
                        'active' => $option['active'] ?? false,
                        'installments_allowed' => $option['installments_allowed'] ?? null,
                        'monthly_installments_limit' => $option['monthly_installments_limit'] ?? null,
                    ];
                }
            }

            $event->paymentOptions()->sync($syncData);
        }
    }

    private function updateEventExamData(Event $event, ?array $data = null): void
    {
        if ($data) {
            $syncData[$data['selected_exam']] = [
                'exam_accessibility_type' => $data['exam_accessibility_type'] ?? null,
                'exam_accessibility_value' => $data['exam_accessibility_value'] ?? null,
                'exam_repeat_delay' => isset($data['exam_repeated']) && $data['exam_repeated'] ? ($data['exam_repeat_delay'] ?? null) : null,
                'whole_amount_should_be_paid' => $data['whole_amount_should_be_paid'] ?? null,
            ];

            $event->exam()->sync($syncData);
        }
    }

    private function updateEventFilesData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $dataToSync = [];
            $folders = $event->dropbox()->first();

            if ($folders && isset($data['selectedFolders'])) {
                $dataToSync[$folders->id] = ['selectedFolders' => json_encode($data['selectedFolders'])];
                $event->dropbox()->sync($dataToSync);
            }
        }
    }

    private function updateEventAudiencesData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->audiences()->sync($data);
        }
    }

    private function updateEventRelatedCoursesData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->relatedCourses()->sync($data);
        }
    }

    private function updateEventTagsData(Event $event, ?array $data = null): void
    {
        if (is_array($data)) {
            $event->tags()->sync($data);
        }
    }

    private function prepareEventAbsencesSettings(Event $event): array
    {
        return [
            'limit' => $event->absences_limit,
            'starting_hours' => $event->absences_start_hours,
        ];
    }

    private function prepareEventAccessesSettings(Event $event): array
    {
        return [
            'access_duration' => $event->access_duration,
            'files_access_till' => $event->files_access_till,
        ];
    }

    private function prepareEventAudienceSettings(Event $event): array
    {
        $event->loadMissing('audiences');

        return [
            'selected_audiences' => $event->audiences?->pluck('id')->toArray(),
            'audiences_list' => Audience::all(),
        ];
    }

    private function prepareEventRelatedCourseSettings(Event $event): array
    {
        $event->loadMissing('relatedCourses');

        return [
            'related_ids' => $event->relatedCourses?->pluck('id')->toArray(),
            'active_courses' => Event::query()->whereIn('status', [
                Event::STATUS_OPEN,
                Event::STATUS_COMPLETED,
                Event::STATUS_CLOSE,
            ])->where('published', 1)->get(),
        ];
    }

    private function prepareEventTagsSettings(Event $event): array
    {
        $event->loadMissing('tags');

        return [
            'selected_tags' => $event->tags?->pluck('id')->toArray(),
            'tags_list' => Tag::all(),
        ];
    }

    private function prepareEventAdministrationSettings(Event $event): array
    {
        return [
            'admin_title' => $event->admin_title ?? $this->generateEventAdminTitle($event),
            'created_on' => $event->created_at->format('d M Y'),
        ];
    }

    private function generateEventAdminTitle(Event $event): string
    {
        $title = $event->created_at->format('Y.m');

        if ($city = $event->city?->first()?->name) {
            $title .= '-' . $city;
        }

        $title .= ' ' . $event->title;

        $event->update(['admin_title' => $title]);

        return $title;
    }

    private function prepareEventCareerPathAnsSkillsSettings(Event $event): array
    {
        $event->loadMissing(['career', 'skills']);

        return [
            'selected_paths' => $event->career?->pluck('id')->toArray(),
            'career_paths' => Career::all(),
            'selected_skills' => $event->skills?->pluck('id')->toArray(),
            'skills' => Skill::all(),
        ];
    }

    private function prepareEventCertificationSettings(Event $event): array
    {
        $event->loadMissing(['eventInfo']);

        return [
            'has_certification' => $event->hasCertificate(),
            'completion_title' => $event->eventInfo->course_certification_completion,
            'diploma_title' => $event->eventInfo->diploma_title,
            'preview_route' => route('certificate-preview', ['template' => '#TEMPLATE#']),
            'certificate_templates' => [
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
            ],
        ];
    }

    private function prepareEventDeliverySettings(Event $event): array
    {
        $event->loadMissing(['delivery', 'city']);

        return [
            'course_delivery' => $event->delivery?->pluck('id')->toArray(),
            'course_city' => $event->city?->first()?->id,
            'available_deliveries' => Delivery::all(),
        ];
    }

    private function prepareEventDiscountCouponsSettings(Event $event): Collection
    {
        $event->loadMissing('coupons');

        return $event->coupons;
    }

    private function prepareEventDynamicAdvertisingSettings(Event $event): ?DynamicAds
    {
        $event->loadMissing('dynamicAds');

        return $event->dynamicAds;
    }

    private function prepareEventBonusSettings(Event $event): array
    {
        $event->loadMissing('bonusCourse');

        return [
            'selected_courses' => $event->bonusCourse?->pluck('id')->toArray(),
            'exams_required' => $event->bonusCourse?->first()?->pivot?->exams_required ?? false,
            'access_period' => $event->bonusCourse?->first()?->pivot?->access_period ?? null,
            'available_courses' => Event::query()->whereIn('status', [
                Event::STATUS_OPEN,
                Event::STATUS_COMPLETED,
                Event::STATUS_CLOSE,
            ])->where('published', 1)->get(),
        ];
    }

    private function prepareEventFilesSettings(Event $event): array
    {
        $event->loadMissing('dropbox');

        $selectedFiles = $event->dropbox
            ->map(function ($dropbox) {
                $selectedFolders = Json::decode($dropbox->pivot->selectedFolders);

                return $selectedFolders['selectedFolders'];
            })
            ->collapse();

        $filesTree = $this->eventFileService
            ->markSelectedFiles(
                $this->eventFileService->buildFileTree(),
                $selectedFiles
            );

        return [
            'attached_files' => $this->eventFileService->addUuidToEachElement($filesTree),
        ];
    }

    private function prepareEventLanguageSettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'selected_language' => $event->eventInfo->language_id,
            'languages' => Language::all(),
        ];
    }

    private function prepareEventPartnershipSettings(Event $event): array
    {
        $event->loadMissing('partners');

        return [
            'selected_partner' => $event->partners?->first()?->id,
            'available_partners' => Partner::all(),
        ];
    }

    private function prepareEventPaymentsSettings(Event $event): array
    {
        $event->loadMissing(['paymentMethod', 'paymentOptions']);

        return [
            'is_free' => $event->isFree(),
            'selected_gateway' => $event->paymentMethod?->first()?->id,
            'gateways' => PaymentMethod::all(),
            'selected_payment_options' => $event->paymentOptions,
            'options' => PaymentOption::all(),
        ];
    }

    private function prepareEventExamsSettings(Event $event): array
    {
        $event->loadMissing('exam');

        $exam = $event->exam->first();

        return [
            'has_exam' => $event->exam()->exists(),
            'selected_exam' => $exam?->id,
            'exam_accessibility_type' => $exam?->pivot?->exam_accessibility_type,
            'exam_accessibility_value' => $exam?->pivot?->exam_accessibility_value,
            'exam_repeat_delay' => $exam?->pivot?->exam_repeat_delay,
            'whole_amount_should_be_paid' => (bool) $exam?->pivot?->whole_amount_should_be_paid,
            'exam_repeated' => (bool) $exam?->pivot?->exam_repeat_delay,
            'exams' => Exam::all(),
        ];
    }

    private function prepareEventSearchSettings(Event $event): array
    {
        $event->loadMissing('metable');

        return [
            'slug' => $event->getSlug() ?? $this->generateEventSlug($event),
            'meta_title' => $event->metable?->meta_title,
            'meta_description' => $event->metable?->meta_description,
        ];
    }

    private function generateEventSlug(Event $event): string
    {
        $slug = Str::slug($event->title);

        $event->update(['slug' => $slug]);

        return $slug;
    }

    private function prepareEventSupportGroupSettings(Event $event): ?string
    {
        return $event->fb_group;
    }

    private function prepareEventSurveySettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'course_satisfaction_url' => $event->eventInfo->course_satisfaction_url,
            'instructors_url' => $event->eventInfo->instructors_url,
            'send_after_days' => $event->eventInfo->send_after_days,
        ];
    }

    private function prepareEventCTASettings(Event $event): array
    {
        $event->loadMissing('eventInfo');

        return [
            'course_page' => $event->eventInfo->cta_course_page,
            'course_page_re_enroll' => $event->eventInfo->cta_course_page_re_enroll,
            'home_page' => $event->eventInfo->cta_home_page,
            'lists' => $event->eventInfo->cta_lists,
            'is_price_visible_on_button' => $event->eventInfo->cta_price_visible_on_button,
            'is_discount_price_visible' => $event->eventInfo->cta_discount_price_visible,
        ];
    }
}
