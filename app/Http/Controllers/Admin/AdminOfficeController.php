<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Office\StoreRequest;
use App\Http\Requests\Office\UpdateRequest;
use App\Office;
use App\OfficeWifi;

class AdminOfficeController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.office');
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 'asd';
        $this->office = Office::get();
        return view('admin.office.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.office.create', $this->data);
    }


    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $office = new Office();
        // generate code
        $office->code = generate_office_code();
        $office->name = $request->office_name;
        $office->latitude = $request->latitude;
        $office->longitude = $request->longitude;
        $office->radius = $request->radius;
        $office->jam_istirahat_awal = $request->jam_istirahat_awal;
        $office->jam_istirahat_akhir = $request->jam_istirahat_akhir;
        $office->save();

        return Reply::redirect(route('admin.office.index'), 'Office created successfully.');
    }

    /**
     * Display the specified resource.
     *[
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->office = Office::findOrFail($id);

        // get wifi
        $this->getWifi = OfficeWifi::where('office_id', $id)->get();
        return view('admin.office.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $office = Office::find($id);
        $office->name = $request->office_name;
        $office->latitude = $request->latitude;
        $office->longitude = $request->longitude;
        $office->radius = $request->radius;
        $office->jam_istirahat_awal = $request->jam_istirahat_awal;
        $office->jam_istirahat_akhir = $request->jam_istirahat_akhir;
        $office->save();

        return Reply::redirect(route('admin.office.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Office::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
