<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Office;
use App\User;
use App\FormAuditOli;
use Carbon\Carbon;
use App\Helper\Files;

class FormAuditOliController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-oli/create',$data);
    }


    public function save(Request $request){
        $validated_data = $request->validate([
            'no_form' => 'required',
            'tanggal' => 'required',
            'users_id' => 'required',
            'kapal_id' => 'required',
            'posisi' => 'required',
            'start_at' => 'required',
            'engine_name' => 'required',
            'running_hours' => 'required',
            'volume' => 'required',
            'real_stock' => 'required',
            'audit_running_hours' => 'required',
            'remark' => 'required',
            'lampiran' => 'required',
            'catatan' => 'required',
            'temuan' => 'required',
            'ttd' => 'required',
            'foto_perwira' => 'required',
        ]);


        if (isset($request->lampiran) && !empty($request->lampiran)) {
            $filename2 = Files::uploadLocalOrS3($request->lampiran, "form-audit-oli/$request->users_id");
            $lampiran = "user-uploads/form-audit-bbm/$request->users_id/$filename2";
        }
        if (isset($request->foto_perwira) && !empty($request->foto_perwira)) {
            $filename3 = Files::uploadLocalOrS3($request->foto_perwira, "form-audit-oli/$request->users_id");
            $foto_perwira = "user-uploads/form-audit-bbm/$request->users_id/$filename3";
        }

        $validated_data['stop_at'] = Carbon::now();
        $validated_data['lampiran'] = $lampiran;
        $validated_data['foto_perwira'] = $foto_perwira;
        
        
        FormAuditOli::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");
    }
}
