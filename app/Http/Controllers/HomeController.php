<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class HomeController extends Controller
{
    public function index()
    {
        if(Auth::check()) {
            return redirect('/home');
        }

        return view('login');
    }

    public function home()
    {
        return view('home');
    }
}
