<?php

namespace Library;

use Config;
use Mail;
use Session;
use PostRider\Account;

//use Library\BackendHelperLib;
use Library\EmailNotificationsLib;

class AccountHelperLib
{
    public function __construct(EmailNotificationsLib $emailNotify)
    {
        $this->emailNotify = $emailNotify;
    }

    /**
     * Uploads files on account
     *
     */
    public function uploadFiles($account_id = 0, $request_obj)
    {
        if ($request_obj->hasFile('comp_image') && $request_obj->file('comp_image')->isValid()) {
            $this->accountFileCleanUp($account_id, 'comp_image');
            $this->doFileUpload($account_id, 'comp_image', $request_obj->file('comp_image'));
        }
    }

    public function accountFileCleanUp($account_id, $file_field = '')
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.account_upload_options.settings.default_uploads');
        $account_dets = Account::where('id', $account_id)->first()->toArray();
        if ($account_dets[$file_field]) {
            if (file_exists($path_to_subfolder.$this->subFolderID($account_id).'/'.$account_dets[$file_field])) {
                unlink($path_to_subfolder.$this->subFolderID($account_id).'/'.$account_dets[$file_field]);
            }
            //perhaps empty cache
            Account::where('id', $account_id)->update([$file_field => '']);
        }
    }

    public function subFolderID($account_id = 0, $path_to_subfolder = 'uploads/accounts/originals/')
    {
        $images_under_folder = Config::get('dpoptions.account_upload_options.settings.images_under_folder');
        if ($images_under_folder > 0) {
        $subfolder_id = intval(floor($account_id / $images_under_folder) + 1);
        return $subfolder_id;
        }
    }

    public function doFileUpload($account_id = 0, $file_field, $file)
    {
        $path_to_subfolder = base_path('public/');
        $path_to_subfolder .= Config::get('dpoptions.account_upload_options.settings.default_uploads');

        $media["original_name"] = $file->getClientOriginalName();
        $media["file_info"] = $file->getClientMimeType();
        $media["size"] = $file->getClientSize()/1024; // this converts it to kB
        $media["path"] = $this->subFolderID($account_id);

        $name_slug_ext = $this->prepareUniqueFilename($media["original_name"], $path_to_subfolder.$media["path"].'/');

        $media["name"] = $name_slug_ext["name"];
        $media["ext"] = '.'.$name_slug_ext["ext"];
        $media["name_ext"] = $name_slug_ext["name_ext"];

        $file->move($path_to_subfolder.$media["path"].'/', $media["name_ext"]);
        Account::where('id', $account_id)->update([$file_field => $media["name_ext"]]);
        //dd($media);
    }

    public function prepareUniqueFilename($orig_name = '', $path_to_subfolder = 'uploads/originals/1/')
    {
        $path_parts = pathinfo($orig_name);
        $res['ext'] = $path_parts['extension'];
        $res['slug'] = str_slug($path_parts['filename'], '-');

        $end = 0;
        $filename = $res['slug'];
        $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
        $res['iter'] = 1;

        while ($end == 0) {
            if (file_exists($check_filepath)) {
                $filename = $res['slug'].'-'.str_random(5);
                $check_filepath = $path_to_subfolder.$filename.'.'.$res['ext'];
                $res['iter']++;
            } else {
                $end = 1;
                break;
            }
        }

        $res['name'] = $filename;
        $res['name_ext'] = $res['name'].'.'.$res['ext'];

        return $res;
    }

    public function imgUrl($account_id = 0, $img_str = '')
    {
        if (($account_id != 0) && (strlen($img_str))) {
            return \URL::to("/company-img").'/'.$this->subFolderID($account_id).'/'.$img_str;
        } else {
            return '';
        }
    }

    public function lowBalanceWarning($account_id = 0)
    {
        return $this->emailNotify->lowBalanceWarning($account_id);
    }

    public function getCurrentAccount()
    {
        $curr_account = [];
        if (Session::has('account.id')) {
            $curr_account = Account::where('id', Session::get('account.id'))->with('defaultStore')->first();
            if ($curr_account) {
                $curr_account = $curr_account->toArray();
            } else {
                $curr_account = [];
            }
        }
        return $curr_account;
    }
}
