<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Helper\Files;
use App\Office;
use App\User;
use App\FormSoundingCargo;
use App\FormSoundingOob;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormSoundingCargoController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-sounding-cargo/create',$data);
    }

    public function save(Request $request){

        if (isset($request->foto1) && !empty($request->foto1)) {
            $filename1 = Files::uploadLocalOrS3($request->foto1, "form-sounding-cargo/$request->user_id");
            $foto1 = "user-uploads/form-sounding-cargo/$request->user_id/$filename1";
        }
        if (isset($request->lampiran) && !empty($request->lampiran)) {
            $filename2 = Files::uploadLocalOrS3($request->lampiran, "form-sounding-cargo/$request->user_id");
            $lampiran = "user-uploads/form-sounding-cargo/$request->user_id/$filename2";
        }
        if (isset($request->upload2) && !empty($request->upload2)) {
            $filename3 = Files::uploadLocalOrS3($request->upload2, "form-sounding-cargo/$request->user_id");
            $upload2 = "user-uploads/form-sounding-cargo/$request->user_id/$filename3";
        }
        
        $current_timestamp = Carbon::now();
        $flight = new FormSoundingCargo;
 
        $flight->no_sounding_cargo = $request->no_form;
        $flight->tannggal = $request->tanggal;
        $flight->user_id = $request->user_id ;
        $flight->office_id = $request->kapal;
        $flight->posisi = $request->posisi;
        $flight->start_at = $request->start;
        $flight->stop_at = $current_timestamp;
        $flight->kompartemen = $request->kompartemen;
        $flight->produk = $request->produk;
        $flight->tinggi_cairan = $request->ketinggian;
        $flight->volume = $request->volume;
        $flight->foto_sounding_cargo = $foto1;
        $flight->sounding_oob_id = $request->no_form;
 
        $flight->save();
 
        $oob = new FormSoundingOob;

        $oob->no_sounding_oob = $request->no_form;
        $oob->tinggi_cairan = $request->ketinggian2;
        $oob->volume = $request->volume2;
        $oob->lampiran = $lampiran;
        $oob->catatan = $request->catatan;
        $oob->temuan = $request->temuan;
        $oob->ttd_oob = $request->ttd;
        $oob->foto_sounding_oob = $upload2;

        $oob->save();
    }
}
