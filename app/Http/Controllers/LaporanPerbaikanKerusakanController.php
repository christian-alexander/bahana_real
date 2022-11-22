<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LaporanKerusakan;
use App\LaporanPenangguhanPekerjaan;
use App\LaporanPerbaikanKerusakan;
use App\Office;
use App\User;
use File;

class LaporanPerbaikanKerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$laporan_perbaikan_kerusakan_id)
    {
        $user = User::find($user_id);
        $getLPK = LaporanPerbaikanKerusakan::with([
          'details',
          'laporanKerusakan'
        ])->leftjoin('users as u_pembuat','u_pembuat.id','laporan_perbaikan_kerusakan.pembuat')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','laporan_perbaikan_kerusakan.diperiksa')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','laporan_perbaikan_kerusakan.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','laporan_perbaikan_kerusakan.mengetahui_2')
            ->where('laporan_perbaikan_kerusakan.id', $laporan_perbaikan_kerusakan_id)
            ->selectRaw('laporan_perbaikan_kerusakan.*,
            u_pembuat.name as name_pembuat,
            u_diperiksa.name as name_diperiksa,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2
            ')
            ->first();
        if (!isset($getLPK) && empty($getLPK)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Laporan Perbaikan Kerusakan not found',
                ]
              ]);
        }
        $can_approve = false;
        $type_approve = '';
        $getFormApproval = GeneralSetting::where('company_id', $user->company_id)->first();
        $getFormApproval = json_decode($getFormApproval->form_approval);

        if (isset($getFormApproval) && !empty($getFormApproval)) {
            foreach ($getFormApproval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='laporan_perbaikan_kerusakan') {
                        $getFormApproval =$val;
                    }
                }
            }
        }

        if ($getLPK->pembuat==$user->id && $getLPK->is_pembuat== 0) {
            $can_approve = true;
            $type_approve ='pembuat';
        }
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='laporan_perbaikan_kerusakan') {
                if ($getFormApproval->diperiksa==$user->id && $getLPK->is_diperiksa == 0 && $getLPK->is_pembuat== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='diperiksa';
                }elseif ($getFormApproval->mengetahui_1==$user->id && $getLPK->is_mengetahui_1 == 0 && $getLPK->is_diperiksa == 1 && $getLPK->is_pembuat== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_1';
                }elseif ($getFormApproval->mengetahui_2==$user->id && $getLPK->is_mengetahui_2 == 0 && $getLPK->is_diperiksa == 1 && $getLPK->is_pembuat== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_2';
                }
            }
        }
        $getLPK->can_approve = $can_approve;
        $getLPK->type_approve = $type_approve;
        return view('iframe.laporan-perbaikan-kerusakan.detail', [
            'data'=> $getLPK,
            'user'=>$user
        ]);
    }
    public function create($user_id,$laporan_kerusakan_id)
    {
        $laporanKerusakan = LaporanKerusakan::find($laporan_kerusakan_id);
        if (!isset($laporanKerusakan) && empty($laporanKerusakan)) {
            return [
                'code' => 500,
                'msg' => 'Data not found'
            ];
        }
        if ($laporanKerusakan->is_pelaksana==1 && $laporanKerusakan->is_diperiksa==1 && $laporanKerusakan->is_mengetahui_1==1 && $laporanKerusakan->is_mengetahui_2==1) {
            $user = User::find($user_id);
            $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
            return view('iframe.laporan-perbaikan-kerusakan.create',[
                "user_id"=> $user_id,
                "laporanKerusakan"=> $laporanKerusakan,
                'listUser' => $listUser
            ]);
        }else{
            return [
                'code' => 500,
                'msg' => 'Laporan kerusakan need to be approved first'
            ];
        }
    }
    public function store(request $request, $user_id,$laporan_kerusakan_id){
        $this->validate($request, [
            'no' => 'required',
            'tanggal' => 'required',
            'bagian_kapal' => 'required',
            'pembuat' => 'required',
            // 'nama_bagian_dan_posisi_di_kapal' => 'required',
        ], [
            'no.required' => 'Nomor tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'bagian_kapal.required' => 'Bagian Kapal tidak boleh kosong',
            'pembuat.required' => 'Pembuat tidak boleh kosong',
            // 'nama_bagian_dan_posisi_di_kapal.required' => 'Nama Bagian dan Posisi di Kapal tidak boleh kosong',
        ]);
        // logic store spk
        $store = LaporanPerbaikanKerusakan::store($request->all(), $user_id,$laporan_kerusakan_id);
        // return [
        //     $user_id,
        //     'success'=>$store['success'],
        //     'msg'=>$store['msg']
        // ];
        return redirect()->route('laporan-perbaikan-kerusakan.create',[
            $user_id,
            $laporan_kerusakan_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function edit($user_id, $laporan_perbaikan_kerusakan_id)
    {
        $data = LaporanPerbaikanKerusakan::with([
          'details',
          'laporanKerusakan'
        ])->where('id', $laporan_perbaikan_kerusakan_id)
            ->selectRaw('*')
            ->first();
        $user = User::find($user_id);
        $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
        
        if (isset($data->signature_applicant) && !empty($data->signature_applicant)) {
            if (file_exists(public_path($data->signature_applicant))) {
                $file = File::get(public_path($data->signature_applicant));
                $data->base64 = 'data:image/png;base64,'.base64_encode($file);
            }
        }
        return view('iframe.laporan-perbaikan-kerusakan.edit',[
            "user_id"=> $user_id,
            "data"=> $data,
            "listUser" => $listUser
        ]);
    }
    public function update(request $request, $user_id, $laporan_perbaikan_kerusakan_id){
        $this->validate($request, [
            'no' => 'required',
            'tanggal' => 'required',
            'bagian_kapal' => 'required',
            'pembuat' => 'required',
        ], [
            'no.required' => 'Nomor tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'bagian_kapal.required' => 'Bagian Kapal tidak boleh kosong',
            'pembuat.required' => 'Pembuat tidak boleh kosong',
        ]);
        // logic store spk
        $store = LaporanPerbaikanKerusakan::updateModel($request->all(), $user_id, $laporan_perbaikan_kerusakan_id);
        return redirect()->route('laporan-perbaikan-kerusakan.edit',[
            $user_id,
            $laporan_perbaikan_kerusakan_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $laporan_perbaikan_kerusakan_id){
        $getLPP = LaporanPerbaikanKerusakan::find($laporan_perbaikan_kerusakan_id);
        if (!isset($getLPP) && empty($getLPP)) {
            return redirect()->route('laporan-perbaikan-kerusakan.detail',[
                $user_id,
                $laporan_perbaikan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        if ($getLPP->is_pembuat==0 || $getLPP->is_diperiksa==0 || $getLPP->is_mengetahui_1==0 || $getLPP->is_mengetahui_2==0) {
            $this->validate($request, [
                'tanda_tangan' => 'required',
            ], [
                'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
            ]);
        }else{
            return redirect()->route('laporan-perbaikan-kerusakan.detail',[
                $user_id,
                $laporan_perbaikan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data already approved'
            ]);
        }

        $approval = LaporanPerbaikanKerusakan::approve($request->all(),$user_id,$getLPP);
        return redirect()->route('laporan-perbaikan-kerusakan.detail',[
            $user_id,
            $laporan_perbaikan_kerusakan_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $laporan_perbaikan_kerusakan_id){
        $getLaporanKerusakan = LaporanPerbaikanKerusakan::find($laporan_perbaikan_kerusakan_id);
        if (!isset($getLaporanKerusakan) && empty($getLaporanKerusakan)) {
            return redirect()->route('laporan-perbaikan-kerusakan.detail',[
                $user_id,
                $laporan_perbaikan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = LaporanPerbaikanKerusakan::reject($request->all(),$user_id, $getLaporanKerusakan);
        return redirect()->route('laporan-perbaikan-kerusakan.detail',[
            $user_id,
            $laporan_perbaikan_kerusakan_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
