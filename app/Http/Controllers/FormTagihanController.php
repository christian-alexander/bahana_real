<?php

namespace App\Http\Controllers;

use App\FormTagihan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Files;

class FormTagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data= FormTagihan::all();
        return view('iframe.tagihan.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
            'pilihan' => 'required',
            'tagihan' => 'required',
            'jatuh_tempo' => 'required',
            'tanggal_serah_kasir' => 'required',
            'attachment' => 'required',
        ]);

        if (isset($request->attachment) && !empty($request->attachment)) {
            $filename = Files::uploadLocalOrS3($request->attachment, "form-tagihan");
            $attachment = "user-uploads/form-tagihan/$filename";
        }

        $validated_data['attachment'] = $attachment;
        
        
        FormTagihan::create($validated_data);

        return redirect()->back()->with('success',"Berhasil ditambahkan.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function show(FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormTagihan $formTagihan)
    {
        //
    }
}
