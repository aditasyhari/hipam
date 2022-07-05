<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use DataTables;
use Exception;
use Validator;
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

    public function bayar()
    {
        try {
            return view('pembayaran.bayar');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function bayarHippam(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bulan' => 'required',
                'bukti' => 'required|max:2048',
            ]);
                
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $bulan_sekarang = date('m');
            $id_pelanggan = Auth::user()->id;

            if($request->bulan > $bulan_sekarang) {
                return back()->with('error', 'Bulan yang dipilih melebihi bulan sekarang.');
            }

            $check_pembayaran = Pembayaran::where('id_pelanggan', $id_pelanggan)->whereMonth('created_at', $request->bulan)->count();
            if($check_pembayaran) {
                return back()->with('error', 'Pembayaran sudah dilakukan, lihat di riwayat.');
            }

            $path = "images/bukti/";
            $bukti = uploads($request->bukti, $path);

            Pembayaran::create([
                'id_pelanggan' => $id_pelanggan,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'alamat' => Auth::user()->alamat,
                'bukti' => $bukti
            ]);

            return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function riwayat()
    {
        try {
            return view('pembayaran.riwayat');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }
}
