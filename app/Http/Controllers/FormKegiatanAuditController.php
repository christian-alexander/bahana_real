<?php

namespace App\Http\Controllers;

use App\FormKegiatanAudit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKegiatanAuditController extends Controller
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
        return view('iframe.kaudit.create');
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
     * @param  \App\FormKegiatanAudit  $formKegiatanAudit
     * @return \Illuminate\Http\Response
     */
    public function show(FormKegiatanAudit $formKegiatanAudit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKegiatanAudit  $formKegiatanAudit
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKegiatanAudit $formKegiatanAudit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKegiatanAudit  $formKegiatanAudit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKegiatanAudit $formKegiatanAudit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKegiatanAudit  $formKegiatanAudit
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKegiatanAudit $formKegiatanAudit)
    {
        //
    }
}
