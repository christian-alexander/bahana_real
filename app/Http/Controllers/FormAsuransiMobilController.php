<?php

namespace App\Http\Controllers;

use App\User;
use App\FormAsuransiMobil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormAsuransiMobilController extends Controller
{
    //
    public function create($user_id)
    {
        $data['user'] = User::find($user_id);

        return view('iframe/form-asuransi-mobil/create',$data);
    }
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
            'wilayah_operasional' => 'required',
            'keterangan_mobil' => 'required', 
            'pengguna' => 'required',
            'asuransi' => 'required', 
            'nilai_asuransi' => 'required|integer', 
            'start_berlaku' => 'required',
            'end_berlaku' => 'required|date|after_or_equal:start_berlaku',
            'posisi_dokumen_asli' => 'required',
            'note' => 'required'
        ]);

        $validated_data['status'] = 1;

        if(FormAsuransiMobil::create($validated_data)){
            //register berhasil
            return redirect()->back()->with('success','Berhasil Input Data');
        }else{
            return redirect()->back()->with('danger','Gagal Input Data');
        }
    }
}
