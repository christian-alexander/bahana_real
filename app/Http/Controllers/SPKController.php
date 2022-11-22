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
use App\User;

class SPKController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detail($user_id,$spk_id)
    {
        $user = User::find($user_id);

        $model = new SPK;

        $getSPK = SPK::with([
          'details',
          'approval'=>function($query){
              $query->leftjoin('users as u','u.id','spk_approval.approved_by')
              ->leftjoin('users as u2','u2.id','spk_approval.rejected_by')
              ->select('spk_approval.*','u.name as approved_by_name','u2.name as rejected_by_name')
              ->orderBy('spk_approval.created_at','DESC');
          },
          'performance'
        ])->join('users as u','u.id','spk.user_id')
            ->where('spk.id', $spk_id)
            ->selectRaw('spk.*,u.name as user_name')
            ->first();
        if (!isset($getSPK) && empty($getSPK)) {
            // TODO: ganti ke param
            return response()->json([
                'error' => [
                  'status' => 404,
                  'message' => 'SPK not found',
                ]
              ]);
        }

        // set can_approve to 0 its mean no one can approve
        $getSPK->can_approve = 0;
        $getSPK->can_change_barang_id = 0;
        $getSPK->can_report_performance = 0;
        $getSPK->can_change_qty = 0;
        $getSPK->can_see_history = 0;
        $getSPK->can_delete_barang = 0;
        $getSPK->can_set_no_pp = 0;
        $getSPK->can_verif = 0;
        $getSPK->require_signature = 0;
        $cabang = [];
        $barang = [];

        // check login user have permission to approve
        // check  status approval
        $check_approval_status = SPKApproval::where('spk_id', $getSPK->id)->orderBy('updated_at','DESC')->first();
        if (!isset($check_approval_status) && empty($check_approval_status)) {
            // jika kosong maka ini adalah approval ke 1
            // check user yang login punya akses untuk approve
            if($model->have_permission($user, 'is_nahkoda')){
                $getSPK->can_approve = 1;
                $getSPK->require_signature = 1;
            }
        }elseif($check_approval_status->status=='approved_1'){
            // ini menunggu approval ke 2
            if($model->have_permission($user, 'is_admin')){
                $getSPK->can_approve = 1;
                $getSPK->can_change_barang_id = 1;
                $getSPK->can_report_performance = 1;
                $barang = MtStock::select('kdstk','nm')->get();
            }
        }elseif($check_approval_status->status=='approved_2'){
            // dd($user->employeeDetail);
            if ($getSPK->keperluan=='mesin') {
                if($user->employeeDetail->is_pe==1){
                    $getSPK->can_approve = 1;
                    $getSPK->can_change_qty = 1;
                }
            }else{
                if($user->employeeDetail->is_pc==1){
                    $getSPK->can_approve = 1;
                    $getSPK->can_change_qty = 1;
                }
            }
            
        
        }elseif($check_approval_status->status=='approved_3'){
            // ini menunggu approval ke 3
            if($model->have_permission($user, 'is_manager')){
                $getSPK->can_approve = 1;
                $getSPK->can_change_qty = 1;
                $getSPK->can_see_history = 1;
                $getSPK->can_delete_barang = 1;
                $getSPK->require_signature = 1;
            }
        }elseif($check_approval_status->status=='approved_4' && $getSPK->verif_spv==0){
            // verif spv
            if($model->have_permission($user, 'is_spv_pembelian')){
                $getSPK->can_set_no_pp = 1;
                $getSPK->can_verif = 1;

                // get data cabang
                $cabang = Lokasi::all();
            }
        }else{
            // selain if diatas berati adalah di reject
        }
        $json_cabang=null;
        if (isset($getSPK->json_cabang) && !empty($getSPK->json_cabang)) {
            $json_cabang = json_decode($getSPK->json_cabang, true);
        }
        // get history
        $activity = SPKActivity::where('spk_id', $spk_id)->get();
        return view('iframe.spk.detail', [
            'data'=> $getSPK,
            'user'=>$user,
            'approval'=> $check_approval_status,
            'activity' => $activity,
            'cabang' => $cabang,
            'json_cabang'=>$json_cabang,
            'barang' => $barang
        ]);
    }
    public function create($user_id)
    {
        // $this->pertanyaan = Pertanyaan::get();
        $barang = MtStock::select('kdstk','nm')->get();
        return view('iframe.spk.create',[
            "user_id" => $user_id,
            "barang" => $barang,
        ]);
    }
    public function store(request $request, $user_id){
        // dd($request->all());
        $this->validate($request, [
            'no' => 'required',
            'tanggal' => 'required',
            'keperluan' => 'required',
            'barang_diminta' => 'required',
            'tanda_tangan' => 'required',
        ], [
            'no.required' => 'No tidak boleh kosong',
            'tanggal.required' => 'tanggal tidak boleh kosong',
            'keperluan.required' => 'keperluan tidak boleh kosong',
            'barang_diminta.required' => 'Setidaknya masukkan 1 barang',
            'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
        ]);
        // logic store spk
        $store = SPK::store($request->all(), $user_id);
        // return [
        //     $user_id,
        //     'success'=>$store['success'],
        //     'msg'=>$store['msg']
        // ];
        return redirect()->route('spk.create',[
            $user_id,
            'success'=>$store['success'],
            'msg'=>$store['msg']
        ]);
    }
    public function approve(request $request,$user_id, $spk_id){
        $getSPK = SPK::find($spk_id);
        if (!isset($getSPK) && empty($getSPK)) {
            return redirect()->route('spk.detail',[
                $user_id,
                $spk_id,
                'success'=>false,
                'msg'=>'Data not found'
            ]);
        }
        $check_approval_status = SPKApproval::where('spk_id', $getSPK->id)->orderBy('updated_at','DESC')->first();
        if (!isset($check_approval_status) && empty($check_approval_status)) {
            $this->validate($request, [
                'tanda_tangan' => 'required',
            ], [
                'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
            ]);
        }elseif($check_approval_status->status=='approved_1'){
            
        }elseif($check_approval_status->status=='approved_3'){
            $this->validate($request, [
                'tanda_tangan' => 'required',
            ], [
                'tanda_tangan.required' => 'Tanda tangan tidak boleh kosong',
            ]);
        }elseif($check_approval_status->status=='approved_4'){
            // $this->validate($request, [
            //     'no_pp' => 'required',
            // ], [
            //     'no_pp.required' => 'No PP tidak boleh kosong',
            // ]);
            $this->validate($request, [
                'cabang' => 'required',
            ], [
                'cabang.required' => 'Cabang tidak boleh kosong',
            ]);
        }

        $approval = SPK::approve($request->all(),$user_id, $getSPK,$check_approval_status);
        return redirect()->route('spk.detail',[
            $user_id,
            $spk_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function reject(request $request,$user_id, $spk_id){
        $approval = SPK::reject($request->all(),$user_id, $spk_id);
        return redirect()->route('spk.detail',[
            $user_id,
            $spk_id,
            'success'=>$approval['success'],
            'msg'=>$approval['msg']
        ]);
    }
    public function delete($user_id,$spk_id, $spk_detail_id){
        $delete = SPKDetail::remove($user_id,$spk_detail_id);
        return redirect()->route('spk.detail',[
            $user_id,
            $spk_id,
            'success'=>$delete['success'],
            'msg'=>$delete['msg']
        ]);
    }
    public function history($barang_id, $start_date, $end_date){
        $data = SPKDetail::where('barang_id', $barang_id)
            ->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)
            ->get();
        $html="";
        $idx = 1;
        foreach ($data as $val) {
            $html.= "<tr>
                <th>$idx</th>
                <td>$val->barang_id</td>
                <td>$val->barang_diminta</td>
                <td>$val->barang_disetujui</td>
                <td>$val->ket</td>
            </tr>";
            $idx++;
        }
        if ($html =="") {
            $html.="<tr>
                <td colspan='5' style='text-align: center'>Tidak ada data</td>
            </tr>";
        }
        return $html;
    }
}
