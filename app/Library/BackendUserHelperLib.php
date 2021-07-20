<?php

namespace Library;

use Illuminate\Support\Facades\Facade;
//use Cartalyst\Sentinel\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use PostRider\Role;
use PostRider\RoleUser;
use PostRider\User;

class BackendUserHelperLib
{
    /*
    public function __construct(Sentinel $auth)
    {
        $this->auth = $auth;
    }
    */

    public function allowedLevel($edited_user_id = 0, $logged_in_user_id = 0, $level = null)
    {
        if ($edited_user_id == 0) {
            return false;
        } else {
            if ($logged_in_user_id == 0) {
                $logged_in_user_id = Sentinel::getUser()->id;
            }

            $loggedInRoles = RoleUser::where('user_id', $logged_in_user_id)->select('role_id')->get()->toArray();
            $editedUserRoles = RoleUser::where('user_id', $edited_user_id)->select('role_id')->get()->toArray();

            //dd($loggedInRoles,$editedUserRoles);

            if (!empty($loggedInRoles)) {
                foreach ($loggedInRoles as $key => $row) {
                    $tmp[] = $row['role_id'];
                }
                $loggedInRoles = $tmp;
            }

            if (!empty($editedUserRoles)) {
                $tmp = array();
                foreach ($editedUserRoles as $key => $row) {
                    $tmp[] = $row['role_id'];
                }
                $editedUserRoles = $tmp;
            }

            //dd($loggedInRoles,$editedUserRoles);

            if (empty($editedUserRoles) || empty($loggedInRoles)) {
                return false;
            } else {

                $loggedInHighestRole = Role::whereIn('id', $loggedInRoles)->select('level')->orderBy('level','desc')->first()->toArray();
                $editedUserHighestRole = Role::whereIn('id', $editedUserRoles)->select('level')->orderBy('level','desc')->first()->toArray();
                //dd($loggedInHighestRole, $editedUserHighestRole);
                if ($loggedInHighestRole['level'] >= $editedUserHighestRole['level']) {
                    if (is_null($level)) {
                        return true;
                    } else {
                        if ($loggedInHighestRole['level'] >= $level) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }

    public function greatestLevel($user_id = 0)
    {
        $loggedInRoles = RoleUser::where('user_id', $user_id)->select('role_id')->get()->toArray();

        if (!empty($loggedInRoles)) {
            foreach ($loggedInRoles as $key => $row) {
                $tmp[] = $row['role_id'];
            }
            $loggedInRoles = $tmp;
            $loggedInHighestRole = Role::whereIn('id', $loggedInRoles)->select('level')->orderBy('level','desc')->first()->toArray();
            return $loggedInHighestRole['level'];
        } else {
            return 0;
        }
    }
}
