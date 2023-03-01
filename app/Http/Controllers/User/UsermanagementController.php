<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsermanagementController extends Controller
{
    public function index()
    {
        return view('usermanagement.user-management');
    }
}
