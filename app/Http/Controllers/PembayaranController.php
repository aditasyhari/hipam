<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use DataTables;
use Exception;
use Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        try {
            if(Auth::user()->role == 'petugas') {
                return view('pembayaran.list-petugas');
            } else {
                return view('pembayaran.list-pelanggan');
            }
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
            $data = Pembayaran::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->make(true);
        }
    }
}
