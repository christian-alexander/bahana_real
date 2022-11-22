<?php

namespace App\Http\Controllers;

use App\FormPerijinan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormPerijinanController extends Controller
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
        return view('iframe.perijinan.create');
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
     * @param  \App\FormPerijinan  $formPerijinan
     * @return \Illuminate\Http\Response
     */
    public function show(FormPerijinan $formPerijinan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormPerijinan  $formPerijinan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormPerijinan $formPerijinan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormPerijinan  $formPerijinan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormPerijinan $formPerijinan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormPerijinan  $formPerijinan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormPerijinan $formPerijinan)
    {
        //
    }
}
