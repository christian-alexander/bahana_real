<?php

namespace App\Http\Controllers;

use App\EmployeeDetails;
use App\GeneralSetting;
use App\Http\Controllers\Controller;
use App\InputPO;
use App\InternalMemo;
use Illuminate\Http\Request;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\Logistik\MtStock;
use App\Office;
use App\PermintaanDana;
use App\SBPBBM;
use App\SoundingPagiPerwira;
use App\SubCompany;
use App\User;
use App\Wilayah;
use File;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Department;

class InputPOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$id)
    {
        $user = User::find($user_id);
        $getData = InputPO::leftjoin('office as o','o.id','input_po.kapal_id')
            ->leftjoin('sub_company as sc','input_po.sub_company_id','sc.id')
            ->where('input_po.id', $id)
            ->selectRaw('input_po.*,
            sc.name as perusahaan,
            o.name as office
            ')
            ->first();
        return view('iframe.input-po.detail', [
            'data'=> $getData,
            'user'=>$user
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);

        $kapal = Office::where('is_kapal',1)->where('company_id', $user->company_id)->get();

        $perusahaan = SubCompany::where('company_id', $user->company_id)->get();


        // get user
        $data_user = \DB::table('users')->get();

        return view('iframe.input-po.create',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "data_user"=> $data_user,
            "perusahaan"=> $perusahaan,
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'wilayah' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_po' => 'required',
            'perusahaan' => 'required',
            'nomor_po' => 'required',
            'customer' => 'required',
            'kapal' => 'required',
            'contact_person' => 'required',
            'jenis_produk' => 'required',
            'quantity' => 'required',
            'nomor_sao' => 'required',
        ], [
            'wilayah.required' => 'Wilayah tidak boleh kosong',
            'jenis_kegiatan.required' => 'Jenis Kegiatan tidak boleh kosong',
            'tanggal_po.required' => 'Tanggal PO tidak boleh kosong',
            'perusahaan.required' => 'Perusahaan tidak boleh kosong',
            'nomor_po.required' => 'Nomor PO tidak boleh kosong',
            'customer.required' => 'Customer tidak boleh kosong',
            'kapal.required' => 'Kapal tidak boleh kosong',
            'contact_person.required' => 'Contact Person tidak boleh kosong',
            'jenis_produk.required' => 'Jenis Produk tidak boleh kosong',
            'quantity.required' => 'Quantity tidak boleh kosong',
            'nomor_sao.required' => 'Nomor SAO tidak boleh kosong',
        ]);
        // logic store 
        $store = InputPO::store($request->all(), $user_id);

        return redirect()->route('input-po.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function edit($user_id,$id)
    {
        $user = User::find($user_id);
        $data = InputPO::leftjoin('office as o','o.id','input_po.kapal_id')
            ->leftjoin('sub_company as sc','input_po.sub_company_id','sc.id')
            ->where('input_po.id', $id)
            ->selectRaw('input_po.*,
            sc.name as perusahaan,
            o.name as office
            ')
            ->first();
        $kapal = Office::where('is_kapal',1)->where('company_id', $user->company_id)->get();

        $perusahaan = SubCompany::where('company_id', $user->company_id)->get();

        // get user
        $data_user = \DB::table('users')->get();

        return view('iframe.input-po.edit',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            "data_user"=> $data_user,
            "perusahaan"=> $perusahaan,
            "data"=> $data,
        ]);
    }

    public function update(request $request, $user_id, $id){
        $this->validate($request, [
            'wilayah' => 'required',
            'jenis_kegiatan' => 'required',
            'tanggal_po' => 'required',
            'perusahaan' => 'required',
            'nomor_po' => 'required',
            'customer' => 'required',
            'kapal' => 'required',
            'contact_person' => 'required',
            'jenis_produk' => 'required',
            'quantity' => 'required',
            'nomor_sao' => 'required',
        ], [
            'wilayah.required' => 'Wilayah tidak boleh kosong',
            'jenis_kegiatan.required' => 'Jenis Kegiatan tidak boleh kosong',
            'tanggal_po.required' => 'Tanggal PO tidak boleh kosong',
            'perusahaan.required' => 'Perusahaan tidak boleh kosong',
            'nomor_po.required' => 'Nomor PO tidak boleh kosong',
            'customer.required' => 'Customer tidak boleh kosong',
            'kapal.required' => 'Kapal tidak boleh kosong',
            'contact_person.required' => 'Contact Person tidak boleh kosong',
            'jenis_produk.required' => 'Jenis Produk tidak boleh kosong',
            'quantity.required' => 'Quantity tidak boleh kosong',
            'nomor_sao.required' => 'Nomor SAO tidak boleh kosong',
        ]);
        // logic store 
        $store = InputPO::updateModel($request->all(), $user_id, $id);

        return redirect()->route('input-po.edit',[
            $user_id,
            $id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
}
