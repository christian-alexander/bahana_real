<?php

namespace App\Http\Controllers;

use App\FormAsuransiJiwa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormAsuransiJiwaController extends Controller
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
        return view('iframe.ajiwa.create');
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
     * @param  \App\FormAsuransiJiwa  $formAsuransiJiwa
     * @return \Illuminate\Http\Response
     */
    public function show(FormAsuransiJiwa $formAsuransiJiwa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormAsuransiJiwa  $formAsuransiJiwa
     * @return \Illuminate\Http\Response
     */
    public function edit(FormAsuransiJiwa $formAsuransiJiwa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormAsuransiJiwa  $formAsuransiJiwa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormAsuransiJiwa $formAsuransiJiwa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormAsuransiJiwa  $formAsuransiJiwa
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormAsuransiJiwa $formAsuransiJiwa)
    {
        //
    }
}
