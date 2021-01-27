<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PengaduanRequest;
use App\Models\Pengaduan;
use Validator;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function index($id = false)
    {
        if($id){
            $data = Pengaduan::findOrfail($id);
            return Helper::success($data, 'Data Detail Pengaduan Berhasil di ambil');
        }else{
            $data = Pengaduan::all();
            return Helper::success($data, 'Data Pengaduan Berhasil di ambil');
        }
    }
    public function store(Request $req)
    {
        $is_valid = Validator::make($req->all(), [
            'kode_pengaduan' => 'unique',
            'nomor_induk' => 'required',
            'nama' => 'required',
            'email' => 'required',
            'no_telp' => 'required',
            'alamat'=>'required',
            'jenis_pengaduan' =>'required',
            'tanggal_laporan' => 'required',
            'laporan' => 'required',
        ]);
        if($is_valid->fails()){
            return Helper::error($is_valid->errors(),'Kesalahan input data', 400);
        }
        if($req->hasFile('berkas_pendukung')){
            $file = request()->file('berkas_pendukung'); 
            $berkas = $file->move('uploads/berkas_pendukung/', time(). '-' . Str::limit(Str::slug(request()->nama), 50, '').'-'.strtotime('now').'.'.$file->getClientOriginalExtension());
        }
        $data = Pengaduan::create([
             'kode_pengaduan' => 'PGD' . mt_rand(10000,99999) . mt_rand(100,999),
             'nomor_induk' => request()->nomor_induk,
             'judul_laporan' => request()->judul_laporan,
             'nama' => request()->nama,
             'email' => request()->email,
             'no_telp' => request()->no_telp,
             'alamat' => request()->alamat,
             'jenis_pengaduan' => request()->jenis_pengaduan,
             'tanggal_laporan' => request()->tanggal_laporan,
             'laporan' => request()->laporan,
             'berkas_pendukung' => !empty($berkas) ? $berkas : '',
             'status' => 'pending',
        ]);
        return Helper::success($data, 'Data Pengaduan berhasil di buat');
    }
 
}
