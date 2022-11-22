<?php

namespace App\Http\Controllers;

use App\FormSAOBunker;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormSAOBunkerController extends Controller
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
        return view('iframe.saobunker.create');
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
     * @param  \App\FormSAOBunker  $formSAOBunker
     * @return \Illuminate\Http\Response
     */
    public function show(FormSAOBunker $formSAOBunker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormSAOBunker  $formSAOBunker
     * @return \Illuminate\Http\Response
     */
    public function edit(FormSAOBunker $formSAOBunker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormSAOBunker  $formSAOBunker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormSAOBunker $formSAOBunker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormSAOBunker  $formSAOBunker
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormSAOBunker $formSAOBunker)
    {
        //
    }
}
