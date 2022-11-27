<?php

namespace App\Http\Controllers;

use App\FormStatusAsetOwnerModels;
use App\Http\Controllers\Controller;
// use App\Models\FormStatusAsetOwnerModels;
use Illuminate\Http\Request;

class FormStatusAsetOwnerControllers extends Controller
{
    //
    public function index()
    {
        // // Ambil kategori
        return view('form_status_aset_owner');
    }
    public function doInput(Request $request)
    {
        $request->validate([
            'njop'=> 'required|integer',
            'luas'=> 'required|integer',
            'posisi_dokumen'=> 'required|integer',
            'tanggal_perolehan'=> 'required|date',
            'tanggal_brankas'=> 'required|date|after_or_equal:tanggal_perolehan',
        ]);

        $data = $request->all();
        $data['status'] = 1;

        if(FormStatusAsetOwnerModels::create($data)){
            //register berhasil
            return redirect('/bahana/form_status_aset_owner')->with('pesan','Berhasil Input Data');
        }else{
            return redirect('/bahana/form_status_aset_owner')->with('pesan','Gagal Input Data');
        }
    }
}
