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
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Model\Role;

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
        $user = Auth::user();
        $user = User::with('role')->find($user['id']);
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

        $user = User::find($request->user_id);

        if($request->file('photo')){
            //parse old image
            $old_image = $user->image;
            //parse input photo
            $content = $request->file('photo');
            $name = explode(".",$content->getClientOriginalName());
            $name = $name[0];

            //$user= Auth::user();
            //create new instance
            $media = new Media;
            $media->original_name = $content->getClientOriginalName();
            $media->name = $name;
            $media->ext = $content->guessClientExtension();
            $media->file_info = $content->getClientMimeType();
            $media->mediable_id = $user['id'];
        }

        if (Gate::denies('update', auth()->user())) {

            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change data for a default user.')]);
        }



        $user->update(
            $request->merge(['picture' => $request->photo ? $path_name = $request->photo->store('profile_user', 'public') : null])
                ->except([$request->hasFile('photo') ? '' : 'picture'])


        );
       if($request->file('photo')){
            $name = explode('profile_user/',$path_name);
            $size = getimagesize('uploads/'.$path_name);
            $media->original_name = $name[1];
            $media->width = $size[0];
            $media->height = $size[1];
            $user->image()->save($media);

            //delete old image
            //fetch old image

            if($old_image != null){
                //delete from folder
                unlink('uploads/profile_user/'.$old_image['original_name']);
                //delete from db
                $old_image->delete();
            }
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

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }
}
