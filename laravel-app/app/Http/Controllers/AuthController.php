<?php

namespace App\Http\Controllers;

class AuthController extends Controller
{
    public function showVerify()
    {
        return view('auth.verify');
    }
}
