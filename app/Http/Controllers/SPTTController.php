<?php

namespace App\Http\Controllers;

use App\Cabang;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Pertanyaan\StoreRequest;
use App\Http\Requests\Pertanyaan\UpdateRequest;
use App\Logistik\Lokasi;
use App\Logistik\MtStock;
use App\Pertanyaan;
use App\SPK;
use App\SPKActivity;
use App\SPKApproval;
use App\SPKDetail;
use App\SPTT;
use App\User;
use File;

class SPTTController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$sptt_id)
    {
        $user = User::find($user_id);
        $getSPTT = SPTT::with([
          'details'
        ])->leftjoin('users as u_diserahkan_oleh','u_diserahkan_oleh.id','sptt.diserahkan_oleh')
            ->leftjoin('users as u_penerima','u_penerima.id','sptt.penerima')
            ->where('sptt.id', $sptt_id)
            ->selectRaw('sptt.*,
            u_diserahkan_oleh.name as diserahkan_oleh_name,
            u_penerima.name as penerima_name')
            ->first();
        if (!isset($getSPTT) && empty($getSPTT)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'Surat Pengiriman dan Tanda Terima not found',
                ]
              ]);
        }
        $getSPTT->can_approve = false;
        $type_approve = '';
        if ($user->id == $getSPTT->diserahkan_oleh && $getSPTT->is_diserahkan_oleh==0) {
            $getSPTT->can_approve = true;
            $type_approve ='diserahkan_oleh';
        }
        if ($user->id == $getSPTT->penerima && $getSPTT->is_penerima_oleh==0 && $getSPTT->is_diserahkan_oleh==1) {
            $getSPTT->can_approve = true;
            $type_approve ='penerima';
        }
        $getSPTT->type_approve = $type_approve;
        return view('iframe.sptt.detail', [
            'data'=> $getSPTT,
            'user'=>$user,
        ]);
    }
    public function create($user_id)
    {
        $user = User::find($user_id);
        $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
        return view('iframe.sptt.create',[
            "user_id"=> $user_id,
            'listUser'=>$listUser
        ]);
    }
    public function store(request $request, $user_id){
        $this->validate($request, [
            'no' => 'required',
            'type' => 'required',
            'posisi_kapal' => 'required',
            'tanggal' => 'required',
            'penerima' => 'required',
            'diserahkan_oleh' => 'required',
            'uraian' => 'required',
        ], [
            'no.required' => 'No tidak boleh kosong',
            'type.required' => 'Type tidak boleh kosong',
            'posisi_kapal.required' => 'Posisi Kapal tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'penerima.required' => 'Penerima tidak boleh kosong',
            'diserahkan_oleh.required' => 'Diserahkan Oleh tidak boleh kosong',
            'uraian.required' => 'Setidaknya masukkan 1 uraian',
        ]);
        // logic store spk
        $store = SPTT::store($request->all(), $user_id);
        return redirect()->route('sptt.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function edit($user_id, $sptt_id)
    {
        $user = User::find($user_id);
        $listUser = User::where('company_id',$user->company_id)->pluck('name','id');
        $data = SPTT::with([
          'details'
        ])->join('users as u','u.id','sptt.diserahkan_oleh')
            ->where('sptt.id', $sptt_id)
            ->selectRaw('sptt.*,u.name as user_name')
            ->first();
        if (isset($data->signature_applicant) && !empty($data->signature_applicant)) {
            if (file_exists(public_path($data->signature_applicant))) {
                $file = File::get(public_path($data->signature_applicant));
                $data->base64 = 'data:image/png;base64,'.base64_encode($file);
            }
        }
        return view('iframe.sptt.edit',[
            "user_id"=> $user_id,
            "data"=> $data,
            "listUser"=> $listUser,
        ]);
    }
    public function update(request $request, $user_id, $sptt_id){
        $this->validate($request, [
            'no' => 'required',
            'type' => 'required',
            'posisi_kapal' => 'required',
            'tanggal' => 'required',
            'penerima' => 'required',
            'diserahkan_oleh' => 'required',
            'uraian' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'no.required' => 'No tidak boleh kosong',
            'type.required' => 'Type tidak boleh kosong',
            'posisi_kapal.required' => 'Posisi Kapal tidak boleh kosong',
            'tanggal.required' => 'Tanggal tidak boleh kosong',
            'penerima.required' => 'Penerima tidak boleh kosong',
            'diserahkan_oleh.required' => 'Diserahkan Oleh tidak boleh kosong',
            'uraian.required' => 'Setidaknya masukkan 1 uraian',
            'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
        ]);
        // logic store spk
        $store = SPTT::updateModel($request->all(), $user_id, $sptt_id);
        return redirect()->route('sptt.edit',[
            $user_id,
            $sptt_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $sptt_id){
        $getSPTT = SPTT::find($sptt_id);
        if (!isset($getSPTT) && empty($getSPTT)) {
            return redirect()->route('sptt.detail',[
                $user_id,
                $sptt_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        if ($getSPTT->is_approved==0) {
            $this->validate($request, [
                'tanda_tangan' => 'required',
            ], [
                'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
            ]);
        }else{
            return redirect()->route('sptt.detail',[
                $user_id,
                $sptt_id,
                'success'=>false,
                'msg'=>'Data already approved'
            ]);
        }

        $approval = SPTT::approve($request->all(),$user_id,$getSPTT);
        return redirect()->route('sptt.detail',[
            $user_id,
            $sptt_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $sptt_id){
        $getSPTT = SPTT::find($sptt_id);
        if (!isset($getSPTT) && empty($getSPTT)) {
            return redirect()->route('sptt.detail',[
                $user_id,
                $sptt_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $approval = SPTT::reject($request->all(),$user_id, $getSPTT);
        return redirect()->route('sptt.detail',[
            $user_id,
            $sptt_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
}
