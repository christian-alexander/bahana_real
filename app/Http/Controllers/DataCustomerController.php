<?php

namespace App\Http\Controllers;

use App\DataCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('iframe.datacustomer.create');
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
     * @param  \App\DataCustomer  $dataCustomer
     * @return \Illuminate\Http\Response
     */
    public function show(DataCustomer $dataCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataCustomer  $dataCustomer
     * @return \Illuminate\Http\Response
     */
    public function edit(DataCustomer $dataCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataCustomer  $dataCustomer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataCustomer $dataCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataCustomer  $dataCustomer
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataCustomer $dataCustomer)
    {
        //
    }
}
