<?php

namespace App\Http\Controllers;

use App\FormTagihan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormTagihanController extends Controller
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
        return view('iframe.tagihan.create');
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
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function show(FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormTagihan $formTagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormTagihan  $formTagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormTagihan $formTagihan)
    {
        //
    }
}
