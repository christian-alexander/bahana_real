<?php

namespace App\Http\Controllers;

use App\FormRencanaPemberangkatanEbis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormRencanaPemberangkatanEbisController extends Controller
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
        return view('iframe.pemberangkatanebis.create');
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
     * @param  \App\FormRencanaPemberangkatanEbis  $formRencanaPemberangkatanEbis
     * @return \Illuminate\Http\Response
     */
    public function show(FormRencanaPemberangkatanEbis $formRencanaPemberangkatanEbis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormRencanaPemberangkatanEbis  $formRencanaPemberangkatanEbis
     * @return \Illuminate\Http\Response
     */
    public function edit(FormRencanaPemberangkatanEbis $formRencanaPemberangkatanEbis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormRencanaPemberangkatanEbis  $formRencanaPemberangkatanEbis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormRencanaPemberangkatanEbis $formRencanaPemberangkatanEbis)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormRencanaPemberangkatanEbis  $formRencanaPemberangkatanEbis
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormRencanaPemberangkatanEbis $formRencanaPemberangkatanEbis)
    {
        //
    }
}
