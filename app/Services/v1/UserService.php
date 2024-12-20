<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\CMSFile;
use App\Enums\EventStatusEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\ChunkReadFilter;
use App\Model\Delivery;
use App\Model\Event;
use App\Model\Option;
use App\Model\ShoppingCart;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserService
{
    public function filterQuery(array $data): Builder
    {
        return User::query()
            ->with($this->getRelations())
            ->when(array_key_exists('event_id', $data), function ($q) use ($data) {
                $q->whereHas('eventList', function ($q) use ($data) {
                    $q->where('events.id', $data['event_id']);
                });
            })
            ->when(array_key_exists('delivery_id', $data), function ($q) use ($data) {
                $q->whereHas('eventList', function ($q) use ($data) {
                    $q->whereHas('delivery', function ($q) use ($data) {
                        $q->where('deliveries.id', $data['delivery_id']);
                    });
                });
            })
            ->when(array_key_exists('coupon_id', $data), function ($q) use ($data) {
                $q->whereHas('eventList', function ($q) use ($data) {
                    $q->whereHas('coupons', function ($q) use ($data) {
                        $q->where('coupons.id', $data['coupon_id']);
                    });
                });
            })
            ->when(array_key_exists('enrolment_status', $data), function ($q) use ($data) {
                if ($data['enrolment_status'] === 'abandoned') {
                    $timestamp = Cache::get(
                        'timestamp-last-time-check-abandoned-cart',
                        Carbon::now()->subYears(10)->format('Y-m-d H:i:s')
                    );
                    $listIds = ShoppingCart::with('user')
                        ->where('created_at', '>', $timestamp)
                        ->pluck('identifier')
                        ->toArray();

                    $q->whereIn('id', $listIds);
                } elseif ($data['enrolment_status'] === 'completed') {
                    $q->whereHas('eventList', function ($q) {
                        $q->where('status', EventStatusEnum::Completed->value);
                    });
                } elseif ($data['enrolment_status'] === 'sponsored') {
                    $q->whereHas('eventList', function ($q) {
                        $q->whereHas('ticket', function ($q) {
                            $q->where('type', 'Sponsored');
                        });
                    });
                }
            })
            ->when(array_key_exists('transaction_status', $data), function ($q) use ($data) {
                if ($data['transaction_status'] === 'paid') {
                    $q->whereHas('transactions');
                } elseif ($data['transaction_status'] === 'free') {
                    $q->whereDoesntHave('transactions');
                }
            })
            ->when(array_key_exists('profile_status', $data), function ($q) use ($data) {
                $q->where('users.profile_status', $data['profile_status']);
            })->when(array_key_exists('account_status', $data), function ($q) use ($data) {
                $q->where('users.account_status', $data['account_status']);
            })->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->whereDate('users.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('roles', $data), function ($q) use ($data) {
                $q->whereHas('roles', function ($q) use ($data) {
                    $q->whereIn('roles.id', array_map('intval', $data['roles']));
                });
            })->when(array_key_exists('not_equal_roles', $data), function ($q) use ($data) {
                $q->whereDoesntHave('roles', function ($q) use ($data) {
                    $q->whereIn('roles.id', array_map('intval', $data['not_equal_roles']));
                });
            })->when(array_key_exists('tags', $data), function ($q) use ($data) {
                $q->whereHas('tags', function ($q) use ($data) {
                    $q->whereIn('tags.id', array_map('intval', $data['tags']));
                });
            })->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('users.firstname', 'like', '%' . $data['query'] . '%')
                        ->orWhere('users.lastname', 'like', '%' . $data['query'] . '%')
                        ->orWhere('users.email', 'like', '%' . $data['query'] . '%');
                });
            })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc');
    }

    public function store(array $data): User
    {
        $user = new User($data);
        $user->meta_title = $data['firstname'] . ' ' . $data['lastname'];
        $user->meta_description = $data['firstname'] . ' ' . $data['lastname'];
        $user->password = Hash::make($data['password']);
        if (array_key_exists('receipt_details', $data)) {
            $user->receipt_details = json_encode($data['receipt_details']);
        }
        if (array_key_exists('invoice_details', $data)) {
            $user->invoice_details = json_encode($data['invoice_details']);
        }
        if (array_key_exists('photo', $data)) {
            $user->profile_image_id = $data['photo'] ?? null;
        }
        $user->save();

        if (array_key_exists('roles', $data)) {
            $user->roles()->sync($data['roles']);
        }
        if (array_key_exists('work_cities', $data)) {
            $user->cities()->sync($data['work_cities']);
        }
        if (array_key_exists('career_paths', $data)) {
            $user->careerPaths()->sync($data['career_paths']);
        }
        if (array_key_exists('skills', $data)) {
            $user->skills()->sync($data['skills']);
        }
        if (array_key_exists('tags', $data)) {
            $user->tags()->sync($data['tags']);
        }

        $user->load($this->getRelations());

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        if (array_key_exists('password', $data)) {
            $user->password = Hash::make($data['password']);
        }
        if (array_key_exists('receipt_details', $data)) {
            $user->receipt_details = json_encode($data['receipt_details']);
        }
        if (array_key_exists('invoice_details', $data)) {
            $user->invoice_details = json_encode($data['invoice_details']);
        }
        if (array_key_exists('photo', $data)) {
            if ($data['photo'] === 0) {
                $user->profile_image_id = null;
            } elseif (CMSFile::query()->find($data['photo'])) {
                $user->profile_image_id = $data['photo'] ?? null;
            } else {
                throw ValidationException::withMessages(['photo' => ['The selected photo is invalid.']]);
            }
        }
        $user->save();

        if (array_key_exists('roles', $data)) {
            $user->roles()->sync($data['roles']);
        }
        if (array_key_exists('work_cities', $data)) {
            $user->cities()->sync($data['work_cities']);
        }
        if (array_key_exists('career_paths', $data)) {
            $user->careerPaths()->sync($data['career_paths']);
        }
        if (array_key_exists('skills', $data)) {
            $user->skills()->sync($data['skills']);
        }
        if (array_key_exists('tags', $data)) {
            $user->tags()->sync($data['tags']);
        }

        $user->load($this->getRelations());

        return $user;
    }

    public function adminsCounts(): array
    {
        return [
            'admins'   => User::query()->whereHas('roles', function ($q) {
                $q->whereIn('role_users.role_id', [RoleEnum::Admin->value, RoleEnum::SuperAdmin->value]);
            })->count(),
            'managers' => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::Manager->value);
            })->count(),
            'editors'  => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::Author->value);
            })->count(),
        ];
    }

    public function studentsCounts(): array
    {
        return [
            'total'  => $this->getTotalStudents(),
            'active' => $this->getActiveStudents(),
        ];
    }

    public function getRelations(): array
    {
        return [
            'profile_image',
            'image',
            'roles',
            'tags',
        ];
    }

    public function importUsersFromFile(UploadedFile $file): array
    {
        $filename = $file->getClientOriginalName();
        $tempPath = $file->getRealPath();
        $extension = explode('.', $filename)[1];

        $reader = IOFactory::createReader(ucfirst($extension));

        $chunkSize = 2048;
        $chunkFilter = new ChunkReadFilter();

        $reader->setReadFilter($chunkFilter);

        for ($startRow = 2; $startRow <= 240; $startRow += $chunkSize) {
            $chunkFilter->setRows($startRow, $chunkSize);
            $spreadsheet = $reader->load($tempPath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        }

        if ($sheetData) {
            if ($sheetData[1]['A'] === 'firstname') {
                $this->fromExample($sheetData);
            } else {
                $this->fromExportedFile($sheetData);
            }
        }

        return [];
    }

    public function validateUser(array $userData): array
    {
        $validator = Validator::make($userData, [
            'firstname'    => 'min:3',
            'lastname'     => 'min:3',
            'email'        => 'required|email',
            'mobile'       => 'nullable|digits:10',
            'telephone'    => 'nullable|digits:10',
            'address'      => 'nullable|min:3',
            'address_num'  => 'nullable|numeric',
            'country_code' => 'nullable',
            'ticket_type'  => 'sometimes|min:3',
            'ticket_price' => 'nullable|digits_between:-10000000,10000000',
            'afm'          => 'nullable|digits:8',
            'birthday'     => 'nullable|date_format:d-m-Y',
            'event_id'     => 'sometimes|numeric',
        ]);

        $data = [];

        $data['pass'] = $validator->passes();

        if ($validator->errors()) {
            $data['errors'] = $validator->errors();
        }

        return $data;
    }

    private function renamedKeys(): array
    {
        return [
            'A'  => 'id',
            'B'  => 'firstname',
            'C'  => 'lastname',
            'D'  => 'slug',
            'E'  => 'email',
            'I'  => 'company',
            'J'  => 'company_url',
            'K'  => 'job_title',
            'L'  => 'nationality',
            'M'  => 'genre',
            'N'  => 'birthday',
            'O'  => 'skype',
            'Q'  => 'mobile',
            'R'  => 'telephone',
            'S'  => 'address',
            'T'  => 'address_num',
            'U'  => 'postcode',
            'V'  => 'city',
            'Z'  => 'comment',
            'AA' => 'afm',
            'AE' => 'receipt_details',
            'AF' => 'invoice_details',
            'AG' => 'stripe_id',
            'AI' => 'country',
            'AK' => 'stripe_ids',
            'AL' => 'notes',
            'AO' => 'pm_type',
            'AP' => 'pm_last_four',
        ];
    }

    private function renamedKeysFromExample(): array
    {
        return [
            'B'  => 'firstname',
            'C'  => 'lastname',
            'D'  => 'email',
            'E'  => 'slug',
            'F'  => 'password',
            'G'  => 'company',
            'H'  => 'company_url',
            'I'  => 'job_title',
            'J'  => 'nationality',
            'K'  => 'genre',
            'L'  => 'birthday',
            'M'  => 'skype',
            'N'  => 'mobile',
            'O'  => 'telephone',
            'P'  => 'address',
            'Q'  => 'address_num',
            'R'  => 'postcode',
            'S'  => 'city',
            'T'  => 'afm',
            'U'  => 'billing',
            'V'  => 'billname',
            'W'  => 'billemail',
            'X'  => 'billaddress',
            'Y'  => 'billaddressnum',
            'Z'  => 'billpostcode',
            'AA' => 'billcity',
            'AB' => 'billcountry',
            'AC' => 'billstate',
            'AD' => 'billafm',
            'AE' => 'country',
            'AF' => 'notes',
            'AG' => 'stripe_id',
            'AH' => 'companyname',
            'AI' => 'companyprofession',
            'AJ' => 'companyafm',
            'AK' => 'companydoy',
            'AL' => 'companyaddress',
            'AM' => 'companyaddressnum',
            'AN' => 'companypostcode',
            'AO' => 'companycity',
        ];
    }

    private function createKCIdForUser(User $user): bool
    {
        if ($user->kc_id == null) {
            $optionKC = Option::where('abbr', 'website_details')->first();
            $next = $optionKC->value;

            $next_kc_id = str_pad($next, 4, '0', STR_PAD_LEFT);

            $user->kc_id = 'KC-' . date('ym') . $next_kc_id;

            if ($next == 9999) {
                $next = 1;
            } else {
                $next = $next + 1;
            }

            $optionKC->value = $next;
            $optionKC->save();

            return $user->save();
        }

        return false;
    }

    private function fromExample(array $sheetData)
    {
        $renamedKeys = $this->renamedKeysFromExample();

        foreach ($sheetData as $key => $data) {
            if ($key === 1) {
                continue;
            }
            $newArray[] = array_combine(
                array_map(function ($key) use ($renamedKeys) {
                    return $renamedKeys[$key] ?? $key;
                }, array_keys($data)),
                $data
            );
        }

        foreach ($newArray as $data) {
            $data['receipt_details'] = json_encode([
                'billing'        => $data['billing'] ?? null,
                'billname'       => $data['billname'] ?? null,
                'billemail'      => $data['billemail'] ?? null,
                'billaddress'    => $data['billaddress'] ?? null,
                'billaddressnum' => $data['billaddressnum'] ?? null,
                'billpostcode'   => $data['billpostcode'] ?? null,
                'billcity'       => $data['billcity'] ?? null,
                'billcountry'    => $data['billcountry'] ?? null,
                'billstate'      => $data['billstate'] ?? null,
                'billafm'        => $data['billafm'] ?? null,
            ]);
            $data['invoice_details'] = json_encode([
                'companyname'       => $data['companyname'] ?? null,
                'companyprofession' => $data['companyprofession'] ?? null,
                'companyafm'        => $data['companyafm'] ?? null,
                'companydoy'        => $data['companydoy'] ?? null,
                'companyaddress'    => $data['companyaddress'] ?? null,
                'companyaddressnum' => $data['companyaddressnum'] ?? null,
                'companypostcode'   => $data['companypostcode'] ?? null,
                'companycity'       => $data['companycity'] ?? null,
            ]);
            $validations = $this->validateUser($data);

            if (!$validations['pass']) {
                continue;
            }
            $data['password'] = Hash::make($data['password']);

            /** @var User $user */
            $user = User::query()->updateOrCreate(['email' => $data['email']], $data);

            $user->roles()->sync([RoleEnum::KnowCrunchStudent->value], false);
            $this->createKCIdForUser($user);
        }
    }

    private function fromExportedFile(array $sheetData)
    {
        $renamedKeys = $this->renamedKeys();

        foreach ($sheetData as $data) {
            $newArray[] = array_combine(
                array_map(function ($key) use ($renamedKeys) {
                    return $renamedKeys[$key] ?? $key;
                }, array_keys($data)),
                $data
            );
        }

        foreach ($newArray as $data) {
            $validations = $this->validateUser($data);

            if (!$validations['pass']) {
                continue;
            }
            /** @var User $user */
            $user = User::query()->updateOrCreate(['email' => $data['email']], $data);

            $user->roles()->sync([RoleEnum::KnowCrunchStudent->value], false);
            $this->createKCIdForUser($user);
        }
    }

    private function getTotalStudents(): array
    {
        $deliveries = Delivery::all();

        foreach ($deliveries as $delivery) {
            $count = $delivery->event()->withCount([
                'users' => function ($query) {
                    $query->select(DB::raw('count(distinct(users.id))'));
                },
            ])->get()->sum('users_count');

            $counts['data'][] = [
                'label' => $delivery->name,
                'count' => $count,
            ];
        }

        $counts['total'] = User::query()->count();

        return $counts;
    }

    private function getActiveStudents(): array
    {
        $deliveries = Delivery::all();

        foreach ($deliveries as $delivery) {
            $count = $delivery->event()->withCount([
                'users' => function ($q) {
                    $q->where('event_user.expiration', '>', Carbon::now());
                },
            ])->get()->sum('users_count');

            $counts['data'][] = [
                'label' => $delivery->name,
                'count' => $count,
            ];
        }

        $counts['total'] = User::query()->whereHas('events', function ($q) {
            $q->where('event_user.expiration', '>', Carbon::now());
        })->count();

        return $counts;
    }

    public function getInstructorsByCourse(): array
    {
        $classroomInstructors = User::query()->whereHas('roles', function ($q) {
            $q->where('roles.name', 'Instructor');
        })->whereHas('instructorEvents', function ($q) {
            $q->whereHas('delivery', function ($q) {
                $q->where('deliveries.id', Delivery::where('delivery_type', 'classroom')->first()->id);
            });
        })->count();

        $videoInstructors = User::query()->whereHas('roles', function ($q) {
            $q->where('roles.name', 'Instructor');
        })->whereHas('instructorEvents', function ($q) {
            $q->whereHas('delivery', function ($q) {
                $q->where('deliveries.id', Delivery::where('delivery_type', 'video')->first()->id);
            });
        })->count();

        $virtualClassInstructors = User::query()->whereHas('roles', function ($q) {
            $q->where('roles.name', 'Instructor');
        })->whereHas('instructorEvents', function ($q) {
            $q->whereHas('delivery', function ($q) {
                $q->where('deliveries.id', Delivery::where('delivery_type', 'virtual_class')->first()->id);
            });
        })->count();

        return [
            [
                'label' => 'All',
                'count' => $classroomInstructors + $videoInstructors + $virtualClassInstructors,
            ],
            [
                'label' => Delivery::where('delivery_type', 'classroom')->first()->name,
                'count' => $classroomInstructors,
            ],
            [
                'label' => Delivery::where('delivery_type', 'video')->first()->name,
                'count' => $videoInstructors,
            ],
            [
                'label' => Delivery::where('delivery_type', 'virtual_class')->first()->name,
                'count' => $virtualClassInstructors,
            ],
        ];
    }

    public function attachToCourse(User $user, Event $event): bool
    {
        $user->eventList()->sync([$event->id], false);

        $tagIds = $event->tags()->pluck('tags.id')->toArray();

        $user->tags()->syncWithoutDetaching($tagIds);

        return true;
    }

    public function getUserCourses(User $user, array $data): LengthAwarePaginator
    {
        $courses = $user->eventList()->with([
            'transactions' => function ($q) use ($user) {
                $q->whereHas('user', function ($query) use ($user) {
                    $query->where('id', $user);
                })->orderBy('created_at');
            },
            'delivery',
            'tickets'      => function ($q) use ($user) {
                $q->where('event_user_ticket.user_id', $user->id);
            },
        ])->when(array_key_exists('date_from', $data), function ($q) use ($data) {
            $q->whereDate('events.created_at', '>=', Carbon::parse($data['date_from']));
        })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
            $q->whereDate('events.created_at', '<=', Carbon::parse($data['date_to']));
        })->when(array_key_exists('query', $data), function ($q) use ($data) {
            $q->where(function ($q) use ($data) {
                $q->where('events.title', 'like', '%' . $data['query'] . '%');
            });
        })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);

        foreach ($courses as $course) {
            $course->progress = round($course->progress($user));
        }

        return $courses;
    }

    public function getUserSubscriptions(User $user, array $data): LengthAwarePaginator
    {
        $subscriptions = $user->eventSubscriptions()
            ->with([
                'transactions' => function ($q) use ($user) {
                    $q->whereHas('user', function ($query) use ($user) {
                        $query->where('id', $user);
                    })->orderBy('created_at');
                },
            ])->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);

        foreach ($subscriptions as $subscription) {
            $event = $subscription->event()->where('subscription_user_event.user_id', $user->id)->first();
            $subscription->progress = round($event->progress($user));
        }

        return $subscriptions;
    }
}
