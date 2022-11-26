<?php

namespace App\Http\Controllers;

use App\User;

use App\Office;
use App\Helper\Files;
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
        if (isset($request->foto) && !empty($request->foto)) {
            $filename = Files::uploadLocalOrS3($request->foto, "form-audit-tanki/$request->user_id");
            $foto = "user-uploads/form-audit-tanki/$request->user_id/$filename";
        }

        FormAuditTanki::create([
            'user_id' => $request->user_id,
            'office_id' => $request->office_id,
            'no_form' => $request->no_form,
            'tanggal' => $request->tanggal,
            'posisi' => $request->posisi,
            'start_at' => date('Y-m-d H:i:s',$request->start_at),
            'stop_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'catatan' => $request->catatan,
            'foto' => $foto,
            'temuan' => $request->temuan,
            'ttd' => $request->ttd,
        ]);

        return redirect()->back();
    }
}
