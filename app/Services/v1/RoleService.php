<?php

declare(strict_types=1);

namespace App\Services\v1;

use App\Model\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    public function filter(array $data): LengthAwarePaginator
    {
        return Role::query()
            ->select('roles.*')
            ->with($this->getRelations())
            ->withCount('users')
            ->when(array_key_exists('query', $data), function ($q) use ($data) {
                $q->where(function ($q) use ($data) {
                    $q->where('roles.name', 'like', '%' . $data['query'] . '%');
                });
            })
            ->orderBy($data['order_by'] ?? 'level', $data['order_type'] ?? 'desc')
            ->paginate($data['per_page'] ?? 25);
    }

    public function store(array $data): Role
    {
        $role = new Role($data);
        $role->save();

        $role->load($this->getRelations());

        return $role;
    }

    public function update(Role $role, array $data): Role
    {
        $role->fill($data);
        $role->save();

        $role->load($this->getRelations());

        return $role;
    }

    public function getRelations(): array
    {
        return [
        ];
    }
}
