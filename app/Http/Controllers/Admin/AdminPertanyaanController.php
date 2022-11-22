<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Pertanyaan\StoreRequest;
use App\Http\Requests\Pertanyaan\UpdateRequest;
use App\Pertanyaan;

class AdminPertanyaanController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Pertanyaan';
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
        // return 'asd';
        $this->pertanyaan = Pertanyaan::get();
        return view('admin.pertanyaan.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pertanyaan.create', $this->data);
    }


    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $pertanyaan = new Pertanyaan();
        $pertanyaan->pertanyaan = $request->pertanyaan;
        $pertanyaan->save();

        return Reply::redirect(route('admin.pertanyaan.index'), 'Pertanyaan created successfully.');
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
        $this->pertanyaan = Pertanyaan::findOrFail($id);
        return view('admin.pertanyaan.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $office = Pertanyaan::find($id);
        $office->pertanyaan = $request->pertanyaan;
        $office->save();

        return Reply::redirect(route('admin.pertanyaan.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pertanyaan::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
