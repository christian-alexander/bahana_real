<?php

namespace App\Http\Controllers;
use App\FormAuditKasCabang;
use App\Office;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FormAuditKasCabangController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();
        return view('iframe/form-audit-kas-cabang/create',$data);
    }


    public function save(Request $request){
        $current_timestamp = Carbon::now();
        FormAuditKasCabang::create([
            'no_form' => $request->no_form,
            'tanggal' => $request->tanggal,
            'users_id' => $request->user_id,
            'cabang_id' => $request->lokasi_cabang,
            'posisi' => $request->posisi,
            'start_at' => $request->start_at,
            'stop_at' => $current_timestamp,
            'catatan' => $request->catatan,
            'foto' => $request->foto,
            'temuan' => $request->temuan,
            'ttd' => $request->ttd,
        ]);
    }
}
