<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengumuman;
use Auth;
use Exceptin;

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
        try {
            $info = Pengumuman::orderBy('id', 'desc')->get();
    
            return view('home', compact(['info']));
        } catch (Exception $e) {
            return view('error');
        }
    }
}
