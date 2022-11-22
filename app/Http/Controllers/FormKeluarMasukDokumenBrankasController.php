<?php

namespace App\Http\Controllers;

use App\FormKeluarMasukDokumenBrankas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKeluarMasukDokumenBrankasController extends Controller
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
        return view('iframe.keluarmasukdoc.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FormKeluarMasukDokumenBrankas  $formKeluarMasukDokumenBrankas
     * @return \Illuminate\Http\Response
     */
    public function show(FormKeluarMasukDokumenBrankas $formKeluarMasukDokumenBrankas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKeluarMasukDokumenBrankas  $formKeluarMasukDokumenBrankas
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKeluarMasukDokumenBrankas $formKeluarMasukDokumenBrankas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKeluarMasukDokumenBrankas  $formKeluarMasukDokumenBrankas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKeluarMasukDokumenBrankas $formKeluarMasukDokumenBrankas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKeluarMasukDokumenBrankas  $formKeluarMasukDokumenBrankas
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKeluarMasukDokumenBrankas $formKeluarMasukDokumenBrankas)
    {
        //
    }
}
