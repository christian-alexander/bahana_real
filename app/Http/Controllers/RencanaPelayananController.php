<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\InputPO;
use Illuminate\Http\Request;
use App\Office;
use App\RencanaPelayanan;
use App\User;

class RencanaPelayananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = RencanaPelayanan::leftjoin('office as o','o.id','rencana_pelayanan.kapal_id')
            ->leftjoin('input_po as ip','rencana_pelayanan.input_po_id','ip.id')
            ->where('rencana_pelayanan.id', $id)
            ->selectRaw('rencana_pelayanan.*,
            ip.no_po as no_po,
            o.name as office
            ')
            ->first();
        return view('iframe.rencana-pelayanan.detail', [
            'data'=> $getData,
            'user'=>$user
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        $kapal = Office::where('is_kapal',1)->where('company_id', $user->company_id)->get();

        // get user
        $data_user = \DB::table('users')->get();

        // input po
        $input_po = InputPO::all();

        return view('iframe.rencana-pelayanan.create',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "input_po"=> $input_po,
            "data_user"=> $data_user
        ]);
    }
    public function store(request $request, $user_id){
        // return $request->all();
        $this->validate($request, [
            'input_po' => 'required',
            'tanggal_rencana_bunker' => 'required',
            'nama_oob' => 'required',
            'kapal' => 'required',
            'nomor_rfb' => 'required',
        ], [
            'input_po.required' => 'Input PO tidak boleh kosong',
            'tanggal_rencana_bunker.required' => 'Tanggal Rencana Bunker tidak boleh kosong',
            'nama_oob.required' => 'Nama OOB tidak boleh kosong',
            'kapal.required' => 'Kapal tidak boleh kosong',
            'nomor_rfb.required' => 'Nomor RFB tidak boleh kosong',
        ]);
        // logic store 
        $store = RencanaPelayanan::store($request->all(), $user_id);

        return redirect()->route('rencana-pelayanan.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function edit($user_id,$id)
    {
        $user = User::find($user_id);
        $data = RencanaPelayanan::leftjoin('office as o','o.id','rencana_pelayanan.kapal_id')
            ->leftjoin('input_po as ip','rencana_pelayanan.input_po_id','ip.id')
            ->where('rencana_pelayanan.id', $id)
            ->selectRaw('rencana_pelayanan.*,
            ip.no_po as no_po,
            o.name as office
            ')
            ->first();
        $kapal = Office::where('is_kapal',1)->where('company_id', $user->company_id)->get();

        // get user
        $data_user = \DB::table('users')->get();

        // input po
        $input_po = InputPO::all();

        return view('iframe.rencana-pelayanan.edit',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "input_po"=> $input_po,
            "data_user"=> $data_user,
            "data"=> $data,
        ]);
    }

    public function update(request $request, $user_id, $id){
        $this->validate($request, [
            'input_po' => 'required',
            'tanggal_rencana_bunker' => 'required',
            'nama_oob' => 'required',
            'kapal' => 'required',
            'nomor_rfb' => 'required',
        ], [
            'input_po.required' => 'Input PO tidak boleh kosong',
            'tanggal_rencana_bunker.required' => 'Tanggal Rencana Bunker tidak boleh kosong',
            'nama_oob.required' => 'Nama OOB tidak boleh kosong',
            'kapal.required' => 'Kapal tidak boleh kosong',
            'nomor_rfb.required' => 'Nomor RFB tidak boleh kosong',
        ]);
        // logic store 
        $store = RencanaPelayanan::updateModel($request->all(), $user_id, $id);

        return redirect()->route('rencana-pelayanan.edit',[
            $user_id,
            $id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function detailPO($id)
    {
        $getData = InputPO::leftjoin('office as o','o.id','input_po.kapal_id')
            ->leftjoin('sub_company as sc','input_po.sub_company_id','sc.id')
            ->where('input_po.id', $id)
            ->selectRaw('input_po.*,
            sc.name as perusahaan,
            o.name as office
            ')
            ->first();
        return $getData;
    }
}
