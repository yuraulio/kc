<?php
/*

=========================================================
* Argon Dashboard PRO - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard-pro-laravel
* Copyright 2018 Creative Tim (https://www.creative-tim.com) & UPDIVISION (https://www.updivision.com)

* Coded by www.creative-tim.com & www.updivision.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

*/

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Model\Role;
use App\Model\User;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Role::class);
    }

    /**
     * Display a listing of the roles.
     *
     * @param \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function index(Role $model)
    {
        $this->authorize('manage-users', User::class);

        return view('roles.index', ['roles' => $model->all()]);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @param  \App\Model\Role  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleRequest $request, Role $model)
    {
        $model->create($request->all());

        return redirect()->route('role.index')->withStatus(__('Role successfully created.'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  \App\Model\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \App\Http\Requests\RoleRequest  $request
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleRequest $request, Role $role)
    {
        //dd('from store controller role');
        $role->update($request->all());

        return redirect()->route('role.index')->withStatus(__('Role successfully updated.'));
    }

    public function destroy(Role $role)
    {
        if (!$role->users->isEmpty()) {
            return redirect()->route('role.index')->withErrors(__('This role has items attached and can\'t be deleted.'));
        }

        $role->delete();

        return redirect()->route('role.index')->withStatus(__('Role successfully deleted.'));
    }

    public function fetchAllRoles()
    {
        $roles = Role::select('name')->get()->toArray();

        return $roles;
    }
}
