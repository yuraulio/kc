<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Enums\RoleEnum;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

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
}
