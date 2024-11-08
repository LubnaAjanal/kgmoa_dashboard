<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewsController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function register()
    {
        return view('registeredUsers');
    }

    public function settings()
    {
        return view('settings');
    }

    public function userAttendance()
    {
        return view('addAttendance');

    }

}