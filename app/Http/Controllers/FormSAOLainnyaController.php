<?php

namespace App\Http\Controllers;

use App\FormSAOLainnya;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormSAOLainnyaController extends Controller
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
        return view('iframe.saolainnya.create');
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
     * @param  \App\FormSAOLainnya  $formSAOLainnya
     * @return \Illuminate\Http\Response
     */
    public function show(FormSAOLainnya $formSAOLainnya)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormSAOLainnya  $formSAOLainnya
     * @return \Illuminate\Http\Response
     */
    public function edit(FormSAOLainnya $formSAOLainnya)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormSAOLainnya  $formSAOLainnya
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormSAOLainnya $formSAOLainnya)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormSAOLainnya  $formSAOLainnya
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormSAOLainnya $formSAOLainnya)
    {
        //
    }
}
