<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\Cabang;
use App\ClusterWorkingHour;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ClusterWorkingHour\StoreRequest;
use App\Http\Requests\ClusterWorkingHour\UpdateRequest;
use App\LogAddendum;
use App\Office;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClusterWorkingHourController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.cluster_working_hour');
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
        $this->office = ClusterWorkingHour::get();
        return view('admin.cluster-working-hour.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cluster-working-hour.create', $this->data);
    }


    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $office = new ClusterWorkingHour();
        $office->name = $request->cluster_name;
        $office->type = $request->type;
        if ($request->type=='daily') {
            $office->json = $this->setJsonDaily($request);
        }else{
            $office->start_hour = $request->start_hour;
            $office->end_hour = $request->end_hour;
        }
        $office->save();

        return Reply::redirect(route('admin.cluster-working-hour.index'), 'Data created successfully.');
    }

    public function setJsonDaily($data){
        $json = [
            "senin" => [
                "jam_masuk" => $data["senin_jam_masuk"],
                "jam_pulang" => $data["senin_jam_pulang"],
                "istirahat_awal" => $data["senin_istirahat_awal"],
                "istirahat_akhir" => $data["senin_istirahat_akhir"],
            ],
            "selasa" => [
                "jam_masuk" => $data["selasa_jam_masuk"],
                "jam_pulang" => $data["selasa_jam_pulang"],
                "istirahat_awal" => $data["selasa_istirahat_awal"],
                "istirahat_akhir" => $data["selasa_istirahat_akhir"],
            ],
            "rabu" => [
                "jam_masuk" => $data["rabu_jam_masuk"],
                "jam_pulang" => $data["rabu_jam_pulang"],
                "istirahat_awal" => $data["rabu_istirahat_awal"],
                "istirahat_akhir" => $data["rabu_istirahat_akhir"],
            ],
            "kamis" => [
                "jam_masuk" => $data["kamis_jam_masuk"],
                "jam_pulang" => $data["kamis_jam_pulang"],
                "istirahat_awal" => $data["kamis_istirahat_awal"],
                "istirahat_akhir" => $data["kamis_istirahat_akhir"],
            ],
            "jumat" => [
                "jam_masuk" => $data["jumat_jam_masuk"],
                "jam_pulang" => $data["jumat_jam_pulang"],
                "istirahat_awal" => $data["jumat_istirahat_awal"],
                "istirahat_akhir" => $data["jumat_istirahat_akhir"],
            ],
            "sabtu" => [
                "jam_masuk" => $data["sabtu_jam_masuk"],
                "jam_pulang" => $data["sabtu_jam_pulang"],
                "istirahat_awal" => $data["sabtu_istirahat_awal"],
                "istirahat_akhir" => $data["sabtu_istirahat_akhir"],
            ],
            "minggu" => [
                "jam_masuk" => $data["minggu_jam_masuk"],
                "jam_pulang" => $data["minggu_jam_pulang"],
                "istirahat_awal" => $data["minggu_istirahat_awal"],
                "istirahat_akhir" => $data["minggu_istirahat_akhir"],
            ]
            ];
            $json = json_encode($json);
            return $json;
    }
    public function getJsonDaily($data, $array = false){
        if ($array) {
            $json = json_decode($data, true);
            if (!isset($json['senin']) && empty($json['senin'])){
                $json['senin'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['selasa']) && empty($json['selasa'])){
                $json['selasa'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['rabu']) && empty($json['rabu'])){
                $json['rabu'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['kamis']) && empty($json['kamis'])){
                $json['kamis'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['jumat']) && empty($json['jumat'])){
                $json['jumat'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['sabtu']) && empty($json['sabtu'])){
                $json['sabtu'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json['minggu']) && empty($json['minggu'])){
                $json['minggu'] = [
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }
        }else{
            $json = json_decode($data);
            if (!isset($json->senin) && empty($json->senin)){
                $json->senin = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->selasa) && empty($json->selasa)){
                $json->selasa = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->rabu) && empty($json->rabu)){
                $json->rabu = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->kamis) && empty($json->kamis)){
                $json->kamis = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->jumat) && empty($json->jumat)){
                $json->jumat = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->sabtu) && empty($json->sabtu)){
                $json->sabtu = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }elseif(!isset($json->minggu) && empty($json->minggu)){
                $json->minggu = (object)[
                    "jam_masuk" => "12:00 AM",
                    "jam_pulang" => "12:00 AM",
                    "istirahat_awal" => "12:00 AM",
                    "istirahat_akhir" => "12:00 AM",
                ];
            }
        }
        return $json;
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
        $clusterWorkingHour = ClusterWorkingHour::findOrFail($id);
        if ($clusterWorkingHour->type == 'daily') {
            $this->jsonData = $this->getJsonDaily($clusterWorkingHour->json);
        }else{
            //
        }
        $this->clusterWorkingHour = $clusterWorkingHour;
        return view('admin.cluster-working-hour.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $now = Carbon::now()->addHours(7);
            // return $request->all(); 
            $office = ClusterWorkingHour::find($id);
            $old = $office->replicate();
            $office->name = $request->cluster_name;
            $office->type = $request->type;
            if ($request->type=='daily') {
                $office->json = $this->setJsonDaily($request);
                $office->start_hour = null;
                $office->end_hour = null;
            }else{
                $office->json = null;
                $office->start_hour = $request->start_hour;
                $office->end_hour = $request->end_hour;
            }
            $office->save();

            // update attendance yang hari ini dengan kondisi id cluster sama dengan id cluster user yg di attendance
            // get all attendance on this day
            $attendances = DB::table('attendances')->join('employee_details as ed','ed.user_id','attendances.user_id')
                ->whereDate('attendances.clock_in_after_timezone',$now->copy()->format('Y-m-d'))
                ->where('ed.cluster_working_hour_id',$office->id)
                ->select('attendances.id','attendances.cluster_meta','ed.cluster_working_hour_id')
                ->get();
            foreach ($attendances as $attendance) {
                DB::update('update attendances set cluster_meta = ? where id = ?', 
                [json_encode($office), $attendance->id]);
            }
            // log addendum
            LogAddendum::logCluster($old, $office);

            DB::commit();
            return Reply::redirect(route('admin.cluster-working-hour.index'), __('messages.updatedSuccessfully'));
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::redirect(route('admin.cluster-working-hour.index'), $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClusterWorkingHour::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
