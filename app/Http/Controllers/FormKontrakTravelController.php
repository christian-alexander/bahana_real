<?php

namespace App\Http\Controllers;

use App\FormKontrakTravel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKontrakTravelController extends Controller
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
        return view('iframe.kontraktravel.create');
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
     * @param  \App\FormKontrakTravel  $formKontrakTravel
     * @return \Illuminate\Http\Response
     */
    public function show(FormKontrakTravel $formKontrakTravel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKontrakTravel  $formKontrakTravel
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKontrakTravel $formKontrakTravel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKontrakTravel  $formKontrakTravel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKontrakTravel $formKontrakTravel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKontrakTravel  $formKontrakTravel
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKontrakTravel $formKontrakTravel)
    {
        //
    }
}
