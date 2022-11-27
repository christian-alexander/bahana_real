<?php

namespace App\Http\Controllers;

use App\FormAsuransiJiwaOwner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormAsuransiJiwaOwnerController extends Controller
{
    //
    public function create()
    {
        // // Ambil kategori
        return view('iframe/form-asuransi-jiwa-owner/create');
    }
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
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
