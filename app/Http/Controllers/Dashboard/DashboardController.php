<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;

class DashboardController extends Controller
{
    public function searchUser($search_term){

        $searchData = [];
        $users = User::searchUsers($search_term);

        foreach($users as $user){
            $searchData[] = ['name' => $user->firstname . ' ' . $user->lastname, 'link' => '/admin/user/' . $user->id . '/edit', 'email'=>$user->email];
        }

        return response()->json([
            'searchData' => $searchData
        ]);
    }
}
