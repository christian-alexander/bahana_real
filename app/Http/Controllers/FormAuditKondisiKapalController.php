<?php

namespace App\Http\Controllers;

use App\User;

use App\Office;
use App\Helper\Files;
use Illuminate\Http\Request;
use App\FormAuditKondisiKapal;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class FormAuditKondisiKapalController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-kondisi-kapal/create',$data);
    }

    public function save(Request $request){
        dd(date('Y-m-d H:i:s',$request->start_at));
        if (isset($request->foto) && !empty($request->foto)) {
            $filename = Files::uploadLocalOrS3($request->foto, "form-audit-kondisi-kapal/$request->user_id");
            $foto = "user-uploads/form-audit-kondisi-kapal/$request->user_id/$filename";
        }

        FormAuditKondisiKapal::create([
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
