<?php

namespace App\Http\Controllers;

use App\FormKontrakBBM;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKontrakBBMController extends Controller
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
        return view('iframe.kontrakbbm.create');
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
     * @param  \App\FormKontrakBBM  $formKontrakBBM
     * @return \Illuminate\Http\Response
     */
    public function show(FormKontrakBBM $formKontrakBBM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKontrakBBM  $formKontrakBBM
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKontrakBBM $formKontrakBBM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKontrakBBM  $formKontrakBBM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKontrakBBM $formKontrakBBM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKontrakBBM  $formKontrakBBM
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKontrakBBM $formKontrakBBM)
    {
        //
    }
}
