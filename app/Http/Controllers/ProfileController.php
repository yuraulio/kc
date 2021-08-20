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

use Gate;
use App\Model\User;
use App\Model\Media;
use App\Model\Activation;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Model\Role;
use App\Http\Controllers\MediaController;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        //dd('from index');
        return view('profile.edit');
    }

    public function updateRole(Request $request)
    {
        //dd($request->all());
        $updateUser = $request->userId;
        $user = User::with('role')->find($updateUser);
        $user->role()->sync($request->role_id);

        return back()->withStatus(__('Profile role successfully updated.'));
    }

    public function update_billing(Request $request)
    {
        $user = User::find($request->user_id);

        if($user['receipt_details'] != null){
            $data['receipt'] = json_decode($user['receipt_details'], true);

            $receipt['billname'] = $request->billname;
            $receipt['billsurname'] = $request->billsurname;
            $receipt['billaddress'] = $request->billaddress;
            $receipt['billaddressnum'] = $request->billaddressnum;
            $receipt['billpostcode'] = $request->billpostcode;
            $receipt['billcity'] = $request->billcity;

        }else{
            $receipt['billname'] = $request->billname;
            $receipt['billsurname'] = $request->billsurname;
            $receipt['billaddress'] = $request->billaddress;
            $receipt['billaddressnum'] = $request->billaddressnum;
            $receipt['billpostcode'] = $request->billpostcode;
            $receipt['billcity'] = $request->billcity;
        }

        if($user['invoice_details'] != null){
            $data['invoice'] = json_decode($user['invoice_details'], true);

            $invoice['companyname'] = $request->companyname;
            $invoice['companyprofession'] = $request->companyprofession;
            $invoice['companyafm'] = $request->companyafm;
            $invoice['companydoy'] = $request->companydoy;
            $invoice['companyaddress'] = $request->companyaddress;
            $invoice['companyaddressnum'] = $request->companyaddressnum;
            $invoice['companypostcode'] = $request->companypostcode;
            $invoice['companycity'] = $request->companycity;
        }else{

            $invoice['companyname'] = $request->companyname;
            $invoice['companyprofession'] = $request->companyprofession;
            $invoice['companyafm'] = $request->companyafm;
            $invoice['companydoy'] = $request->companydoy;
            $invoice['companyaddress'] = $request->companyaddress;
            $invoice['companyaddressnum'] = $request->companyaddressnum;
            $invoice['companypostcode'] = $request->companypostcode;
            $invoice['companycity'] = $request->companycity;
        }

        $receipt = json_encode($receipt);
        $invoice = json_encode($invoice);

        $user->update([
            'receipt_details' => $receipt,
            'invoice_details' => $invoice
        ]);

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        //dd($request->all());
        $user = User::with('image')->find($request->user_id);


        //dd($request->file('photo'));



        if (Gate::denies('update', auth()->user())) {

            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }

        if($request->photo){
            (new MediaController)->uploadProfileImage($request, $user->image);
        }

        if(isset($request->status)){
            $status = 1;
        }else{
            $status = 0;
        }


        if($user->statusAccount != null){
            $user->statusAccount->completed = $status;
            $user->push();
            $user->update($request->all());
        }else{
            $activation = new Activation;
            $activation->user_id = $user['id'];
            $activation->completed = $status;

            $activation->save();
        }






        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {

        if (Gate::denies('update', auth()->user())) {
            return back()->withErrors([
                'not_allow_password' => __('You are not allowed to change the password for a default user.')
            ]);
        }


        if($request->user == Auth::user()['id']){
            auth()->user()->update(['password' => Hash::make($request->get('password'))]);
        }else{
            $user = User::find($request->user);
            $user->update(['password' => Hash::make($request->get('password'))]);
        }



        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
