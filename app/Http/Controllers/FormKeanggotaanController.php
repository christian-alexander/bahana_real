<?php

namespace App\Http\Controllers;

use App\FormKeanggotaan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKeanggotaanController extends Controller
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
        return view('iframe.anggota.create');
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
     * @param  \App\FormKeanggotaan  $formKeanggotaan
     * @return \Illuminate\Http\Response
     */
    public function show(FormKeanggotaan $formKeanggotaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKeanggotaan  $formKeanggotaan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKeanggotaan $formKeanggotaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKeanggotaan  $formKeanggotaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKeanggotaan $formKeanggotaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKeanggotaan  $formKeanggotaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKeanggotaan $formKeanggotaan)
    {
        //
    }
}
