<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PendingController extends Controller
{
    public function index(){

        return view('pending');
    }

    public function show(){
        Auth::logout();
        return view('auth.login');
    }
}
