<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use DataTables;
use Exception;
use Validator;
use Storage;
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
            $tahun_sekarang = date('Y');
            $id_pelanggan = Auth::user()->id;

            if($request->bulan > $bulan_sekarang) {
                return back()->with('error', 'Bulan yang dipilih melebihi bulan sekarang.');
            }

            $check_pembayaran = Pembayaran::where('id_pelanggan', $id_pelanggan)->where('tahun', $tahun_sekarang)->where('bulan', $request->bulan)->count();
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
                'bulan' => $request->bulan,
                'tahun' => $tahun_sekarang,
                'bukti' => $bukti,
                'status' => 'waiting'
            ]);

            Notifikasi::create([
                'id_pelanggan' => $id_pelanggan,
                'nama' => Auth::user()->nama,
                'tlp' => Auth::user()->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran hippam bulan '.bulan_indo($request->bulan).' '.$tahun_sekarang,
                'petugas' => 1
            ]);

            return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function valid($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);
            $pembayaran->update([
                'status' => 'success',
                'read' => true
            ]);

            Notifikasi::create([
                'id_pelanggan' => $pembayaran->id_pelanggan,
                'nama' => $pembayaran->nama,
                'tlp' => $pembayaran->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran hippam bulan '.bulan_indo($pembayaran->bulan).' '.$pembayaran->tahun.' telah divalidasi.',
                'petugas' => 0
            ]);

            return response()->json('success', 200);
        } catch (Exception $e) {
            return response()->json('error', 500);
            dd($e->getMessage());
        }
    }

    public function tolak($id)
    {
        try {
            $pembayaran = Pembayaran::find($id);
            $pembayaran->update([
                'status' => 'reject',
                'read' => true
            ]);

            Notifikasi::create([
                'id_pelanggan' => $pembayaran->id_pelanggan,
                'nama' => $pembayaran->nama,
                'tlp' => $pembayaran->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran hippam bulan '.bulan_indo($pembayaran->bulan).' '.$pembayaran->tahun.' ditolak. Silahkan unggah ulang bukti yang benar.',
                'petugas' => 0
            ]);

            return response()->json('success', 200);
        } catch (Exception $e) {
            return response()->json('error', 500);
            dd($e->getMessage());
        }
    }

    public function riwayat()
    {
        try {
            $id_pelanggan = Auth::user()->id;
            $tahun_sekarang = date('Y');
            $riwayat = Pembayaran::where('id_pelanggan', $id_pelanggan)->where('tahun', $tahun_sekarang)->get();

            return view('pembayaran.riwayat', compact(['riwayat']));
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function uploadUlang(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'bukti' => 'required|max:2048',
            ]);
                
            if ($validator->fails()) {
                return back()->with('error', 'Unggah Bukti dengan benar.');
            }

            $pembayaran = Pembayaran::find($id);

            $path = "images/bukti/";
            $bukti = uploads($request->bukti, $path);

            Storage::delete($path.'/'.$pembayaran->bukti);

            $pembayaran->update([
                'bukti' => $bukti,
                'status' => 'waiting',
                'read' => false,
                'updated_at' => date("Y-m-d H:i:s")
            ]);

            Notifikasi::create([
                'id_pelanggan' => $pembayaran->id_pelanggan,
                'nama' => $pembayaran->nama,
                'tlp' => $pembayaran->tlp,
                'type' => 'pembayaran',
                'pesan' => 'Pembayaran ulang hippam bulan '.bulan_indo($pembayaran->bulan).' '.$pembayaran->tahun,
                'petugas' => 1
            ]);

            return back()->with('success', 'Pembayaran berhasil, tunggu validasi admin.');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }
}
