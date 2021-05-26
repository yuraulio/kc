<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;

class DashboardController extends Controller
{
    public function searchUser($search_term){
        dd(User::searchUsers($search_term));
    }
}
