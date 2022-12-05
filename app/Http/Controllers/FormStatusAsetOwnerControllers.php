<?php

namespace App\Http\Controllers;

use App\FormStatusAsetOwner;
use App\Http\Controllers\Controller;
// use App\Models\FormStatusAsetOwnerModels;
use Illuminate\Http\Request;

class FormStatusAsetOwnerControllers extends Controller
{
    //
    public function create()
    {
        // // Ambil kategori
        return view('iframe.form-status-aset-owner.create');
    }
    public function doInput(Request $request)
    {


        $validated_data = $request->validate([
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
