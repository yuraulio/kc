<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\RoleEnum;
use App\Http\Controllers\ChunkReadFilter;
use App\Model\Option;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserService
{
    public function filter(array $data): LengthAwarePaginator
    {
        return User::query()
            ->with($this->getRelations())
            ->when(array_key_exists('date_from', $data), function ($q) use ($data) {
                $q->where('users.created_at', '>=', Carbon::parse($data['date_from']));
            })->when(array_key_exists('date_to', $data), function ($q) use ($data) {
                $q->where('users.created_at', '<=', Carbon::parse($data['date_to']));
            })->when(array_key_exists('roles', $data), function ($q) use ($data) {
                $q->whereHas('roles', function ($q) use ($data) {
                    $q->whereIn('roles.id', array_map('intval', $data['roles']));
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
            })->orderBy($data['order_by'] ?? 'id', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);
    }

    public function store(array $data): User
    {
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        if (array_key_exists('receipt_details', $data)) {
            $user->receipt_details = json_encode($data['receipt_details']);
        }
        if (array_key_exists('invoice_details', $data)) {
            $user->invoice_details = json_encode($data['invoice_details']);
        }
        $user->save();

        if (array_key_exists('roles', $data)) {
            $roles = array_map(function ($item) {
                return $item['id'];
            }, $data['roles']);
            $user->roles()->sync($roles);
        }
        if (array_key_exists('skills', $data)) {
            $skills = array_map(function ($item) {
                return $item['id'];
            }, $data['skills']);
            $user->skills()->sync($skills);
        }
        if (array_key_exists('tags', $data)) {
            $tags = array_map(function ($item) {
                return $item['id'];
            }, $data['tags']);
            $user->tags()->sync($tags);
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

        $user->save();

        if (array_key_exists('roles', $data)) {
            $roles = array_map(function ($item) {
                return $item['id'];
            }, $data['roles']);
            $user->roles()->sync($roles);
        }
        if (array_key_exists('skills', $data)) {
            $skills = array_map(function ($item) {
                return $item['id'];
            }, $data['skills']);
            $user->skills()->sync($skills);
        }
        if (array_key_exists('tags', $data)) {
            $tags = array_map(function ($item) {
                return $item['id'];
            }, $data['tags']);
            $user->tags()->sync($tags);
        }

        $user->load($this->getRelations());

        return $user;
    }

    public function userCounts(): array
    {
        return [
            'admins'      => User::query()->whereHas('roles', function ($q) {
                $q->whereIn('role_users.role_id', [RoleEnum::Admin->value, RoleEnum::SuperAdmin->value]);
            })->count(),
            'managers'    => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::Manager->value);
            })->count(),
            'editors'     => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::Author->value);
            })->count(),
            'instructors' => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::Member->value);
            })->count(),
            'students'    => User::query()->whereHas('roles', function ($q) {
                $q->where('role_users.role_id', RoleEnum::KnowCrunchStudent->value);
            })->count(),
        ];
    }

    public function getRelations(): array
    {
        return [
            'profile_image',
            'image',
            'roles',
            'tags',
            'events',
            'activities',
            'subscriptions',
            'invoices',
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
            'AI' => 'country_code',
            'AK' => 'stripe_ids',
            'AL' => 'notes',
            'AO' => 'pm_type',
            'AP' => 'pm_last_four',
        ];
    }

    private function renamedKeysFromExample(): array
    {
        return [
            'A'  => 'firstname',
            'B'  => 'lastname',
            'C'  => 'email',
            'D'  => 'slug',
            'E'  => 'password',
            'F'  => 'company',
            'G'  => 'job_title',
            'H'  => 'nationality',
            'I'  => 'genre',
            'J'  => 'birthday',
            'K'  => 'skype',
            'L'  => 'mobile',
            'M'  => 'telephone',
            'N'  => 'address',
            'O'  => 'address_num',
            'P'  => 'postcode',
            'Q'  => 'city',
            'R'  => 'afm',
            'S'  => 'billing',
            'T'  => 'billname',
            'U'  => 'billemail',
            'V'  => 'billaddress',
            'W'  => 'billaddressnum',
            'X'  => 'billpostcode',
            'Y'  => 'billcity',
            'Z'  => 'billcountry',
            'AA' => 'billstate',
            'AB' => 'billafm',
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
}
