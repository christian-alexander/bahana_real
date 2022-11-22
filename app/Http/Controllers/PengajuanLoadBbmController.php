<?php

namespace App\Http\Controllers;

use App\PengajuanLoadBbm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanLoadBbmController extends Controller
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
        return view('iframe.loadingbbm.create');
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
     * @param  \App\PengajuanLoadBbm  $pengajuanLoadBbm
     * @return \Illuminate\Http\Response
     */
    public function show(PengajuanLoadBbm $pengajuanLoadBbm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PengajuanLoadBbm  $pengajuanLoadBbm
     * @return \Illuminate\Http\Response
     */
    public function edit(PengajuanLoadBbm $pengajuanLoadBbm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PengajuanLoadBbm  $pengajuanLoadBbm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PengajuanLoadBbm $pengajuanLoadBbm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PengajuanLoadBbm  $pengajuanLoadBbm
     * @return \Illuminate\Http\Response
     */
    public function destroy(PengajuanLoadBbm $pengajuanLoadBbm)
    {
        //
    }
}
