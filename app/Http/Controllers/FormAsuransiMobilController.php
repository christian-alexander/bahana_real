<?php

namespace App\Http\Controllers;

use App\FormAsuransiMobil;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormAsuransiMobilController extends Controller
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
        return view('iframe.amobil.create');
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
     * @param  \App\FormAsuransiMobil  $formAsuransiMobil
     * @return \Illuminate\Http\Response
     */
    public function show(FormAsuransiMobil $formAsuransiMobil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormAsuransiMobil  $formAsuransiMobil
     * @return \Illuminate\Http\Response
     */
    public function edit(FormAsuransiMobil $formAsuransiMobil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormAsuransiMobil  $formAsuransiMobil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormAsuransiMobil $formAsuransiMobil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormAsuransiMobil  $formAsuransiMobil
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormAsuransiMobil $formAsuransiMobil)
    {
        //
    }
}
