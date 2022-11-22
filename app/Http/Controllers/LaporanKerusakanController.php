<?php

namespace App\Http\Controllers;

use App\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LaporanKerusakan;
use App\Office;
use App\User;
use File;

class LaporanKerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$laporan_kerusakan_id)
    {
        $user = User::find($user_id);
        $getLaporanKerusakan = LaporanKerusakan::with([
          'details'
        ])->leftjoin('users as u_pelaksana','u_pelaksana.id','laporan_kerusakan.pelaksana')
            ->leftjoin('users as u_diperiksa','u_diperiksa.id','laporan_kerusakan.diperiksa')
            ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','laporan_kerusakan.mengetahui_1')
            ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','laporan_kerusakan.mengetahui_2')
            ->where('laporan_kerusakan.id', $laporan_kerusakan_id)
            ->selectRaw('laporan_kerusakan.*,
            u_pelaksana.name as name_pelaksana,
            u_diperiksa.name as name_diperiksa,
            u_mengetahui_1.name as name_mengetahui_1,
            u_mengetahui_2.name as name_mengetahui_2
            ')
            ->first();
        if (!isset($getLaporanKerusakan) && empty($getLaporanKerusakan)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Laporan Kerusakan not found',
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
                    if ($val->type=='laporan_kerusakan') {
                        $getFormApproval =$val;
                    }
                }
            }
        }

        if ($getLaporanKerusakan->pelaksana==$user->id && $getLaporanKerusakan->is_pelaksana== 0) {
            $can_approve = true;
            $type_approve ='pelaksana';
        }
        if (isset($getFormApproval) && !empty($getFormApproval)) {
            if ($getFormApproval->type=='laporan_kerusakan') {
                if ($getFormApproval->diperiksa==$user->id && $getLaporanKerusakan->is_diperiksa == 0 && $getLaporanKerusakan->is_pelaksana== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='diperiksa';
                }elseif ($getFormApproval->mengetahui_1==$user->id && $getLaporanKerusakan->is_mengetahui_1 == 0 && $getLaporanKerusakan->is_diperiksa == 1 && $getLaporanKerusakan->is_pelaksana== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_1';
                }elseif ($getFormApproval->mengetahui_2==$user->id && $getLaporanKerusakan->is_mengetahui_2 == 0 && $getLaporanKerusakan->is_diperiksa == 1 && $getLaporanKerusakan->is_pelaksana== 1) {
                    // can approve
                    $can_approve = true;
                    $type_approve ='mengetahui_2';
                }
            }
        }
        $getLaporanKerusakan->can_approve = $can_approve;
        $getLaporanKerusakan->type_approve = $type_approve;
        return view('iframe.laporan-kerusakan.detail', [
            'data'=> $getLaporanKerusakan,
            'user'=>$user
        ]);
    }
    public function create($user_id)
    {
        $kapal = Office::where('is_kapal',1)->select('name')->get();
        $user = User::find($user_id);
        $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
        return view('iframe.laporan-kerusakan.create',[
            "user_id"=> $user_id,
            "kapal"=> $kapal,
            'listUser' => $listUser
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'no' => 'required',
            'nama_kapal' => 'required',
            'tanggal' => 'required',
            'bagian_kapal' => 'required',
            'posisi_di_kapal' => 'required',
        ], [
            'no.required' => 'No tidak boleh kosong',
            'nama_kapal.required' => 'Nama Kapal tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'bagian_kapal.required' => 'Bagian Kapal tidak boleh kosong',
            'posisi_di_kapal.required' => 'Setidaknya masukkan 1 kerusakan',
        ]);
        // logic store spk
        $store = LaporanKerusakan::store($request->all(), $user_id);
        return redirect()->route('laporan-kerusakan.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function edit($user_id, $laporan_kerusakan_id)
    {
        $data = LaporanKerusakan::with([
          'details'
        ])->leftjoin('users as u_diperiksa','u_diperiksa.id','laporan_kerusakan.diperiksa')
        ->leftjoin('users as u_mengetahui_1','u_mengetahui_1.id','laporan_kerusakan.mengetahui_1')
        ->leftjoin('users as u_mengetahui_2','u_mengetahui_2.id','laporan_kerusakan.mengetahui_2')
            ->where('laporan_kerusakan.id', $laporan_kerusakan_id)
            ->selectRaw('laporan_kerusakan.*,
            u_diperiksa.name as name_diperiksa,
            u_diperiksa.name as name_mengetahui_1,
            u_diperiksa.name as name_mengetahui_2')
            ->first();
        $kapal = Office::where('is_kapal',1)->select('name')->get();
        $user = User::find($user_id);
        $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
        
        if (isset($data->signature_applicant) && !empty($data->signature_applicant)) {
            if (file_exists(public_path($data->signature_applicant))) {
                $file = File::get(public_path($data->signature_applicant));
                $data->base64 = 'data:image/png;base64,'.base64_encode($file);
            }
        }
        return view('iframe.laporan-kerusakan.edit',[
            "user_id"=> $user_id,
            "data"=> $data,
            "kapal" =>$kapal,
            "listUser" => $listUser
        ]);
    }
    public function update(request $request, $user_id, $laporan_kerusakan_id){
        $this->validate($request, [
            'no' => 'required',
            'nama_kapal' => 'required',
            'tanggal' => 'required',
            'bagian_kapal' => 'required',
            'posisi_di_kapal' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'no.required' => 'No tidak boleh kosong',
            'nama_kapal.required' => 'Nama Kapal tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'bagian_kapal.required' => 'Bagian Kapal tidak boleh kosong',
            'posisi_di_kapal.required' => 'Setidaknya masukkan 1 kerusakan',
            'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
        ]);
        // logic store spk
        $store = LaporanKerusakan::updateModel($request->all(), $user_id, $laporan_kerusakan_id);
        return redirect()->route('laporan-kerusakan.edit',[
            $user_id,
            $laporan_kerusakan_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $laporan_kerusakan_id){
        $getLaporanKerusakan = LaporanKerusakan::find($laporan_kerusakan_id);
        if (!isset($getLaporanKerusakan) && empty($getLaporanKerusakan)) {
            return redirect()->route('laporan-kerusakan.detail',[
                $user_id,
                $laporan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        if ($getLaporanKerusakan->is_diperiksa==0 || $getLaporanKerusakan->is_mengetahui_1==0 || $getLaporanKerusakan->is_mengetahui_2==0) {
            $this->validate($request, [
                'tanda_tangan' => 'required',
            ], [
                'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
            ]);
        }else{
            return redirect()->route('laporan-kerusakan.detail',[
                $user_id,
                $laporan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data already approved'
            ]);
        }

        $approval = LaporanKerusakan::approve($request->all(),$user_id,$getLaporanKerusakan);
        return redirect()->route('laporan-kerusakan.detail',[
            $user_id,
            $laporan_kerusakan_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $laporan_kerusakan_id){
        $getLaporanKerusakan = LaporanKerusakan::find($laporan_kerusakan_id);
        if (!isset($getLaporanKerusakan) && empty($getLaporanKerusakan)) {
            return redirect()->route('laporan-kerusakan.detail',[
                $user_id,
                $laporan_kerusakan_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = LaporanKerusakan::reject($request->all(),$user_id, $getLaporanKerusakan);
        return redirect()->route('laporan-kerusakan.detail',[
            $user_id,
            $laporan_kerusakan_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
