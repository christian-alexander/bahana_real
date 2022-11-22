<?php

namespace App\Http\Controllers;

use App\FormKontrakSewa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormKontrakSewaController extends Controller
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
        return view('iframe.kontraksewa.create');
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
     * @param  \App\FormKontrakSewa  $formKontrakSewa
     * @return \Illuminate\Http\Response
     */
    public function show(FormKontrakSewa $formKontrakSewa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FormKontrakSewa  $formKontrakSewa
     * @return \Illuminate\Http\Response
     */
    public function edit(FormKontrakSewa $formKontrakSewa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FormKontrakSewa  $formKontrakSewa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FormKontrakSewa $formKontrakSewa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FormKontrakSewa  $formKontrakSewa
     * @return \Illuminate\Http\Response
     */
    public function destroy(FormKontrakSewa $formKontrakSewa)
    {
        //
    }
}
