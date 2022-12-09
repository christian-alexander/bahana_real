<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\FormAsuransiJiwaOwner;
use App\Http\Controllers\Controller;

class FormAsuransiJiwaOwnerController extends Controller
{
    //
    public function create($user_id)
    {
        $data['user'] = User::find($user_id);

        return view('iframe/form-asuransi-jiwa-owner/create',$data);
    }
    
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
            'user_id' => 'required',
            'nama_pemilik' => 'required',
            'asuransi' => 'required',
            'tahapan_asuransi' => 'required',
            'nilai_asuransi' => 'required|integer',
            'jumlah_premi'=> 'required|integer',
            'jatuh_tempo'=> 'required|date',
            'tanggal_bayar'=> 'required|date|after_or_equal:jatuh_tempo',
            'note'=> 'required',
        ]);

        $validated_data['status'] = 1;

        if(FormAsuransiJiwaOwner::create($validated_data)){
            //register berhasil
            return redirect()->back()->with('success','Berhasil Input Data');
        }else{
            return redirect()->back()->with('danger','Gagal Input Data');
        }
    }
}
