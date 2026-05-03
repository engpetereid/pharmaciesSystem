<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Login extends Controller
{
    //
    public function showLoginForm(){
        return view('auth.login');
    }
    public function login(Request $request){
        $credentials = $request->only('email', 'password');

    }

}
