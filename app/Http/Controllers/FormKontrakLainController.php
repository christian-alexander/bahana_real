<?php

namespace App\Http\Controllers;

use App\FormKontrakLain;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKontrakLainController extends Controller
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
        return view('iframe.kontraklain.create');
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
     * @param  \App\FormKontrakLain  $formKontrakLain
     * @return \Illuminate\Http\Response
     */
    public function show(FormKontrakLain $formKontrakLain)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKontrakLain  $formKontrakLain
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKontrakLain $formKontrakLain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKontrakLain  $formKontrakLain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKontrakLain $formKontrakLain)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKontrakLain  $formKontrakLain
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKontrakLain $formKontrakLain)
    {
        //
    }
}
