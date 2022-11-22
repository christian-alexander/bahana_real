<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Cabang;
use App\DataTables\Admin\SPKDataTable;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Pertanyaan\StoreRequest;
use App\Http\Requests\Pertanyaan\UpdateRequest;
use App\Pertanyaan;
use App\SPK;

class AdminSPKController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Surat Permintaan Kapal';
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
    public function index(SPKDataTable $dataTable)
    {
        // return view('admin.employees.index', $this->data);
        $this->user_spk = SPK::join('users','users.id','spk.user_id')->select('users.id','users.name')->groupBy('users.id')->get();
        return $dataTable->render('admin.spk.index', $this->data);
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
        $this->spk = SPK::with(['activity','details','user','approval'=> function($q){
            $q->whereIn('status',['approved_1','approved_4','rejected_1','rejected_4']);
        },'approval.approved_by_obj'])->find($id);
        // return $this->spk->activity;
        return view('admin.spk.show', $this->data);
    }
    public function cetak($id)
    {
        $this->setting = Company::where('id', $this->user->id)->first();
        $this->spk = SPK::with(['activity','details','user','approval'=> function($q){
            $q->whereIn('status',['approved_1','approved_4','rejected_1','rejected_4']);
        },'approval.approved_by_obj'])->find($id);
        // return $this->spk->activity;
        return view('admin.spk.cetak', $this->data);
    }

}
