<?php

namespace App\Http\Controllers;

use App\FormSAOTransportir;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormSAOTransportirController extends Controller
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
        return view('iframe.saotransportir.create');
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
     * @param  \App\FormSAOTransportir  $formSAOTransportir
     * @return \Illuminate\Http\Response
     */
    public function show(FormSAOTransportir $formSAOTransportir)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormSAOTransportir  $formSAOTransportir
     * @return \Illuminate\Http\Response
     */
    public function edit(FormSAOTransportir $formSAOTransportir)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormSAOTransportir  $formSAOTransportir
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormSAOTransportir $formSAOTransportir)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormSAOTransportir  $formSAOTransportir
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormSAOTransportir $formSAOTransportir)
    {
        //
    }
}
