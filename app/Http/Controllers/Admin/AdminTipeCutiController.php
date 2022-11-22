<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\TipeCuti;
use Illuminate\Http\Request;
use App\Helper\Reply;
use App\Http\Requests\TipeCuti\StoreRequest;
use App\Http\Requests\TipeCuti\UpdateRequest;
use Illuminate\Support\Facades\DB;

class AdminTipeCutiController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Tipe Cuti';
        // $this->pageTitle = __('app.menu.office');
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
        $this->tipeCutis = TipeCuti::get();
        return view('admin.tipe-cuti.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipe-cuti.create', $this->data);
    }


    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $model = new TipeCuti;
        $model->name = $request->name;
        $model->save();

        return Reply::redirect(route('admin.tipe-cuti.index'), 'Tipe cuti created successfully.');
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
        $this->tipeCuti = TipeCuti::findOrFail($id);
        return view('admin.tipe-cuti.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $model = TipeCuti::find($id);
        $model->name = $request->name;
        $model->save();

        return Reply::redirect(route('admin.tipe-cuti.index'), __('messages.updatedSuccessfully'));
    }
    public function easyUpdate(request $request, $id)
    {
        $model = TipeCuti::find($id);
        $model->limit = $request->leaves;
        $model->save();

        return Reply::success(__('messages.leaveTypeAdded'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipeCuti::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
