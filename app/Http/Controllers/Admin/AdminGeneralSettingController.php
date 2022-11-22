<?php

namespace App\Http\Controllers\admin;

use App\GeneralSetting;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GeneralSetting\StoreRequest;
use App\User;
use Illuminate\Support\Facades\DB;

class AdminGeneralSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'General Setting';
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index(){
        // get setting
        $setting = GeneralSetting::where('company_id',$this->user->company_id)->first();
        if (isset($setting) && !empty($setting)) {
            $getSetting = json_decode($setting->json);
            $setting->general_setting = $getSetting;
            $setting->form_approval = json_decode($setting->form_approval);
        }
        $laporanKerusakan =[];
        $laporanPenangguhanPekerjaan =[];
        $laporanPerbaikanKerusakan =[];
        $internalMemo =[];
        $permintaanDana =[];
        $soundingBunkerPemakaianBbm =[];
        $soundingPagiPerwira =[];
        if (isset($setting->form_approval) && !empty($setting->form_approval)) {
            foreach ($setting->form_approval as $val) {
                if (isset($val->type) && !empty($val->type)) {
                    if ($val->type=='laporan_kerusakan') {
                        $laporanKerusakan =$val;
                    }elseif ($val->type=='laporan_penangguhan_pekerjaan') {
                        $laporanPenangguhanPekerjaan =$val;
                    }elseif($val->type=='laporan_perbaikan_kerusakan'){
                        $laporanPerbaikanKerusakan =$val;
                    }elseif($val->type=='internal_memo'){
                        $internalMemo =$val;
                    }elseif($val->type=='permintaan_dana'){
                        $permintaanDana =$val;
                    }elseif($val->type=='sounding_bunker_pemakaian_bbm'){
                        $soundingBunkerPemakaianBbm =$val;
                    }elseif($val->type=='sounding_pagi_perwira'){
                        $soundingPagiPerwira =$val;
                    }
                }
            }
        }
        $this->laporanKerusakan = $laporanKerusakan;
        $this->laporanPenangguhanPekerjaan = $laporanPenangguhanPekerjaan;
        $this->laporanPerbaikanKerusakan = $laporanPerbaikanKerusakan;
        $this->internalMemo = $internalMemo;
        $this->permintaanDana = $permintaanDana;
        $this->soundingBunkerPemakaianBbm = $soundingBunkerPemakaianBbm;
        $this->soundingPagiPerwira = $soundingPagiPerwira;
        $this->dataUser = User::pluck('name','id');
        $this->dataUserWithAtasan = User::pluck('name','id');
        $this->dataUserWithAtasan->prepend('Atasan 2','atasan_2')->prepend('Atasan 1','atasan_1');
        $this->setting = $setting;
        return view('admin.general-settings.index', $this->data);
    }
    public function store(StoreRequest $request){
        DB::beginTransaction();
        try {
            // check general setting exist
            $setting = GeneralSetting::where('company_id',$this->user->company_id)->first();
            $data_approval = [
            [
                "type" => "laporan_kerusakan",
                "diperiksa" => $request->laporan_kerusakan_diperiksa,
                "mengetahui_1" => $request->laporan_kerusakan_mengetahui_1,
                "mengetahui_2" => $request->laporan_kerusakan_mengetahui_2,
            ],
            [
                "type" => "laporan_penangguhan_pekerjaan",
                "diperiksa" => $request->laporan_penangguhan_pekerjaan_diperiksa,
                "mengetahui_1" => $request->laporan_penangguhan_pekerjaan_mengetahui_1,
                "mengetahui_2" => $request->laporan_penangguhan_pekerjaan_mengetahui_2,
            ],
            [
                "type" => "laporan_perbaikan_kerusakan",
                "diperiksa" => $request->laporan_perbaikan_kerusakan_diperiksa,
                "mengetahui_1" => $request->laporan_perbaikan_kerusakan_mengetahui_1,
                "mengetahui_2" => $request->laporan_perbaikan_kerusakan_mengetahui_2,
            ],
            [
                "type" => "internal_memo",
                "mengetahui_1" => $request->internal_memo_mengetahui_1,
                "mengetahui_2" => $request->internal_memo_mengetahui_2,
            ],
            [
                "type" => "permintaan_dana",
                "diperiksa" => $request->permintaan_dana_diperiksa,
                "mengetahui_1" => $request->permintaan_dana_mengetahui_1,
                "disetujui" => $request->permintaan_dana_disetujui,
            ],
            [
                "type" => "sounding_bunker_pemakaian_bbm",
                "menyaksikan" => $request->sbpbbm_menyaksikan,
                "mengetahui_1" => $request->sbpbbm_mengetahui_1,
                "diperiksa" => $request->sbpbbm_diperiksa,
                "penerima" => $request->sbpbbm_penerima,
            ],
            [
                "type" => "sounding_pagi_perwira",
                "menyetujui" => $request->sounding_pagi_perwira_menyetujui
            ]
            ];
            if (isset($setting) && !empty($setting)) {
                // update
                $setting->json = $this->setSetting($request);
                $setting->form_approval = json_encode($data_approval);
                $setting->save();
            }else{
                $setting = new GeneralSetting;
                $setting->company_id = $this->user->company_id;
                $setting->json = $this->setSetting($request);
                $setting->form_approval = json_encode($data_approval);
                $setting->save();
            }
            DB::commit();
            return Reply::success('Setting updated');
        } catch (\Throwable $e) {
            DB::rollback();
            return Reply::error($e->getMessage());
        }
    }
    public function setSetting($data){
        // $arr =[];
        $arr = [
            'application_version' => isset($data['application_version'])?$data['application_version']:'',
            'update_version' => isset($data['update_version'])?$data['update_version']:'',
            'radius_tracking' => isset($data['radius_tracking'])?$data['radius_tracking']:'',
            'bypass_store_gps_cluster' => isset($data['bypass_store_gps_cluster'])?$data['bypass_store_gps_cluster']:null,
        ];
        $arr = json_encode($arr);
        return $arr;
    }
    // public function getSetting($data){
    //     $arr = json_encode($arr);
    //     return $arr;
    // }
}
