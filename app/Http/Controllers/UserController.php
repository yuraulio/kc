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

use App\Model\Role;
use App\Model\User;
use App\Model\Event;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\Model\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $this->authorize('manage-users', User::class);
        $user = Auth::user();

        $data['all_users'] = $model::count();
        $data['total_graduates'] = 0;

        return view('users.index', ['users' => $model->with('role')->get(), 'data' => $data]);
    }

    /**
     * Show the form for creating a new user
     *
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function create(Role $model)
    {
        return view('users.create', ['roles' => $model->get(['id', 'name'])]);
    }

    public function assignEventToUserCreate(Request $request)
    {
       $user = Auth::user();

       $user_id = $request->user_id;
       $event_ids = $request->event_id;


       if($event_ids != null){
           foreach($event_ids as $event_id){
                $event = Event::find($event_id);
                $event->user()->attach($user_id,['user_id'=> $user_id]);
            }
       }

        return redirect()->route('user.index')->withStatus(__('Course successfully assiged to user.'));
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Model\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create($request->merge([
            'picture' => $request->photo ? $request->photo->store('profile_user', 'public') : null,
            'password' => Hash::make($request->get('password'))
        ])->all());

        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Role  $model
     * @return \Illuminate\View\View
     */
    public function edit(User $user, Role $model)
    {
        $events1 = Event::all();

        return view('users.edit', ['events' => $events1 ,'user' => $user->load('role', 'events'), 'roles' => $model->all()]);
    }


    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $hasPassword = $request->get("password");
        $user->update(
            $request->merge([
                'picture' => $request->photo ? $request->photo->store('profile_user', 'public') : $user->picture,
                'password' => Hash::make($request->get('password'))
            ])->except([$hasPassword ? '' : 'password'])
        );

        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\Model\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->id == 1) {
            return abort(403);
        }

        $user->delete();

        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }
}
