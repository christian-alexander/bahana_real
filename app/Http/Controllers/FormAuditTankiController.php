<?php

namespace App\Http\Controllers;

use App\User;

use App\Office;
use App\Helper\Files;
use App\FormAuditTanki;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        $validated_data = $request->validate([
            'user_id' => 'required',
            'office_id' => 'required',
            'no_form' => 'required',
            'tanggal' => 'required',
            'posisi' => 'required',
            'start_at' => 'required',
            'catatan' => 'required',
            'foto' => 'required',
            'temuan' => 'required',
            'ttd' => 'required',
        ]);

        if (isset($request->foto) && !empty($request->foto)) {
            $filename = Files::uploadLocalOrS3($request->foto, "form-audit-tanki/$request->user_id");
            $foto = "user-uploads/form-audit-tanki/$request->user_id/$filename";
        }

        $validated_data['start_at'] = date('Y-m-d H:i:s',$validated_data['start_at']);
        $validated_data['stop_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $validated_data['foto'] = $foto;
        
        
        FormAuditTanki::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");
    }
}
