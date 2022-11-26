<?php

namespace App\Http\Controllers;

use App\User;

use App\Office;
use App\FormAuditTanki;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormAuditTankiController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-tanki/create',$data);
    }

    public function save(Request $request){
        FormAuditTanki::create([
            'user_id' => $request->user_id,
            'office_id' => $request->office_id,
            'no_form' => $request->no_form,
            'tanggal' => $request->tanggal,
            'posisi' => $request->posisi,
            'start_at' => $request->start_at,
            'stop_at' => $request->stop_at,
            'catatan' => $request->catatan,
            'foto' => $request->foto,
            'temuan' => $request->temuan,
            'ttd' => $request->ttd,
        ]);

        return redirect()->back();
    }
}
