<?php

namespace App\Http\Controllers;
use App\FormAuditKasCabang;
use App\Office;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helper\Files;

class FormAuditKasCabangController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-kas-cabang/create',$data);
    }


    public function save1(Request $request){

        if (isset($request->foto) && !empty($request->foto)) {
            $filename = Files::uploadLocalOrS3($request->foto, "form-audit-kas-cabang/$request->user_id");
            $foto = "user-uploads/form-audit-kas-cabang/$request->user_id/$filename";
        }

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
            'foto' => $foto,
            'temuan' => $request->temuan,
            'ttd' => $request->ttd,
        ]);
    }
    public function save(Request $request){
        $validated_data = $request->validate([
            'no_form' => 'required',
            'tanggal' => 'required',
            'users_id' => 'required',
            'cabang_id' => 'required',
            'posisi' => 'required',
            'start_at' => 'required',
            'catatan' => 'required',
            'foto' => 'required',
            'temuan' => 'required',
            'ttd' => 'required',

        ]);
        
        if (isset($request->foto) && !empty($request->foto)) {
            $filename = Files::uploadLocalOrS3($request->foto, "form-audit-kas-cabang/$request->users_id");
            $foto = "user-uploads/form-audit-kas-cabang/$request->users_id/$filename";
        }

        $validated_data['stop_at'] = Carbon::now();
        $validated_data['foto'] = $foto;
        
        FormAuditKasCabang::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");
    }
}
