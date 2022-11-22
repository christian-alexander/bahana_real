<?php

namespace App\Http\Controllers;

use App\FormPermintaanTiket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormPermintaanTiketController extends Controller
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
        return view('iframe.ptiket.create');
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
     * @param  \App\FormPermintaanTiket  $formPermintaanTiket
     * @return \Illuminate\Http\Response
     */
    public function show(FormPermintaanTiket $formPermintaanTiket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormPermintaanTiket  $formPermintaanTiket
     * @return \Illuminate\Http\Response
     */
    public function edit(FormPermintaanTiket $formPermintaanTiket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormPermintaanTiket  $formPermintaanTiket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormPermintaanTiket $formPermintaanTiket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormPermintaanTiket  $formPermintaanTiket
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormPermintaanTiket $formPermintaanTiket)
    {
        //
    }
}
