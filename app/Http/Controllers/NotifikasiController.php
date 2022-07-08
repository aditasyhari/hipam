<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Exception;
use Validator;
use DataTables;
use Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('notifikasi.list-notifikasi');
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
            if(Auth::user()->role == 'petugas') {
                $data = Notifikasi::where('petugas', 1)->orderBy('id', 'desc')->get();
            } else {
                $data = Notifikasi::where('id_pelanggan', Auth::user()->id)->where('petugas', false)->orderBy('id', 'desc')->get();
            }

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    }
}
