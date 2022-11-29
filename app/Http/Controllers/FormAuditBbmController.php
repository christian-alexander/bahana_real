<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Office;
use App\User;
use App\FormAuditBbm;
use Carbon\Carbon;
use App\Helper\Files;

class FormAuditBbmController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-bbm/create',$data);
    }


    public function save(Request $request){
        $validated_data = $request->validate([
            'no_form' => 'required',
            'tanggal' => 'required',
            'users_id' => 'required',
            'kapal_id' => 'required',
            'posisi' => 'required',
            'start_at' => 'required',
            'kompartemen' => 'required',
            'produk' => 'required',
            'tinggi' => 'required',
            'volume' => 'required',
            'foto_komp' => 'required',
            'sounding_oob' => 'required',
            'lampiran' => 'required',
            'catatan' => 'required',
            'temuan' => 'required',
            'ttd' => 'required',
            'foto_perwira' => 'required',
        ]);

        if (isset($request->foto_komp) && !empty($request->foto_komp)) {
            $filename = Files::uploadLocalOrS3($request->foto_komp, "form-audit-bbm/$request->usesr_id");
            $foto_komp = "user-uploads/form-audit-bbm/$request->users_id/$filename";
        }
        if (isset($request->lampiran) && !empty($request->lampiran)) {
            $filename2 = Files::uploadLocalOrS3($request->lampiran, "form-sounding-cargo/$request->users_id");
            $lampiran = "user-uploads/form-audit-bbm/$request->users_id/$filename2";
        }
        if (isset($request->foto_perwira) && !empty($request->foto_perwira)) {
            $filename3 = Files::uploadLocalOrS3($request->foto_perwira, "form-audit-bbm/$request->users_id");
            $foto_perwira = "user-uploads/form-audit-bbm/$request->users_id/$filename3";
        }

        $validated_data['stop_at'] = Carbon::now();
        $validated_data['foto_komp'] = $foto_komp;
        $validated_data['lampiran'] = $lampiran;
        $validated_data['foto_perwira'] = $foto_perwira;
        
        
        FormAuditBbm::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");
    }
}
