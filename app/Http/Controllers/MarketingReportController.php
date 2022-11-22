<?php

namespace App\Http\Controllers;

use App\MarketingReport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketingReportController extends Controller
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
      return view('iframe.marketingreport.create');
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
     * @param  \App\MarketingReport  $marketingReport
     * @return \Illuminate\Http\Response
     */
    public function show(MarketingReport $marketingReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketingReport  $marketingReport
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketingReport $marketingReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketingReport  $marketingReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketingReport $marketingReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketingReport  $marketingReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketingReport $marketingReport)
    {
        //
    }
}
