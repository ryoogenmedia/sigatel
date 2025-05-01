<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AuthMobileController extends Controller
{
    public function login(){
        return view('auth.mobile.login');
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('mobile.login');
    }
}
