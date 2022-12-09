<?php

namespace App\Http\Controllers;
use App\User;
use App\FormStatusAsetOwner;
use App\Http\Controllers\Controller;
// use App\Models\FormStatusAsetOwnerModels;
use Illuminate\Http\Request;

class FormStatusAsetOwnerControllers extends Controller
{
    //
    public function create($user_id)
    {
        // // Ambil kategori
        
        $d = User::find($user_id);
        return view('iframe.form-status-aset-owner.create',['d' => $d]);
    }
    public function doInput(Request $request)
    {


        $validated_data = $request->validate([
            'user_id' => 'required',
            'wilayah_aset' => 'required',
            'no_sertifikat' => 'required',
            'nama_aset' => 'required',
            'njop' => 'required',
            'luas' => 'required',
            'nama_kepemilikan' => 'required',
            'posisi_dokumen' => 'required',
            'tanggal_perolehan' => 'required',
            'tanggal_masuk_brankas' => 'required|date|after_or_equal:tanggal_perolehan',
            'note' => 'required',
            'status' => 'required',
            'jenis_aset' => 'required',
        ]);

        
        FormStatusAsetOwner::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");


    }
}
