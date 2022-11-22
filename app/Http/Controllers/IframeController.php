<?php

namespace App\Http\Controllers;

use App\Cabang;
use App\ClusterWorkingHour;
use App\Designation;
use App\EmployeeDetails;
use App\Http\Controllers\Admin\IframeBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Office;
use App\User;
use Carbon\Carbon;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\RoleUser;
use App\Skill;
use App\SubCompany;
use App\Team;
use App\Wilayah;

class IframeController extends IframeBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.employees';
        $this->pageIcon = 'icon-user';
    }
    public function editEmployee($id){
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->subcompanys = SubCompany::all();
        $this->wilayahs = Wilayah::all();
        
        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();

        $this->user_orang_kepercayaan = json_decode($this->employeeDetail->user_orang_kepercayaan);
        // dd($this->user_orang_kepercayaan);
        $this->sub_company_orang_kepercayaan = json_decode($this->employeeDetail->sub_company_orang_kepercayaan, true);
        
        $this->active = json_decode($this->employeeDetail->is_on_orang_kepercayaan, true);
        return view('admin.employees.edit-iframe', $this->data);
    }
    public function updateEmployee(request $request, $id)
    {
        $previousUrl = app('url')->previous();
        try {
            $user = User::withoutGlobalScope('active')->findOrFail($id);
            


            $employee = EmployeeDetails::where('user_id', '=', $user->id)->first();
            if (empty($employee)) {
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
            }
            $orang_kepercayaan = null;
            if (isset($request->user_orang_kepercayaan) && !empty($request->user_orang_kepercayaan)) {
                $orang_kepercayaan = array_filter($request->user_orang_kepercayaan);
                $orang_kepercayaan = array_values($orang_kepercayaan);
                $employee->user_orang_kepercayaan = json_encode($orang_kepercayaan);
            }
            if (isset($request->sub_company_orang_kepercayaan) && !empty($request->sub_company_orang_kepercayaan)) {
                $arr_temp=[];
                $arr_temp_status=[];
                $idx=0;
                foreach ($request->sub_company_orang_kepercayaan as $val) {
                    $arr_temp[$orang_kepercayaan[$idx]] = $val;
                    // dd($request->active[$idx] == 1 || $request->active[$idx]);
                    $arr_temp_status[$orang_kepercayaan[$idx]] = isset($request->active[$idx]) ? ($request->active[$idx][0] == 1 || $request->active[$idx][0] == 'on' ? '1' : '0') : '0';
                    $idx++;
                }
                $employee->sub_company_orang_kepercayaan = json_encode($arr_temp);
                $employee->is_on_orang_kepercayaan = json_encode($arr_temp_status);
            }

            $employee->save();

            return redirect()->to($previousUrl.'?'. http_build_query(['success'=>'true']));
        } catch (\Throwable $th) {
            return redirect()->to($previousUrl.'?'. http_build_query(['success'=>'fase','error'=>$th->getMessage()]));
        }
    }
    public function savePermissionEmployee($data)
    {
        $arr_permission = [];
        // set permission sub company
        if (isset($data->subcompany_rule)) {
            foreach ($data->subcompany_rule as $subcompany_rule) {
                array_push($arr_permission, $subcompany_rule);
            }
        }
        // set permission wilayah
        if (isset($data->wilayah_rule)) {
            foreach ($data->wilayah_rule as $wilayah_rule) {
                array_push($arr_permission, $wilayah_rule);
            }
        }
        // set permission cabang
        if (isset($data->cabang_rule)) {
            foreach ($data->cabang_rule as $cabang_rule) {
                array_push($arr_permission, $cabang_rule);
            }
        }
        $arr_permission = json_encode($arr_permission);

        return $arr_permission;
    }
    public function saveApprovalLevel($data)
    {
        $arr_approval_level = [
            0 => isset($data->persetujuan_satu) && !empty($data->persetujuan_satu) ? $data->persetujuan_satu : '',
            1 => isset($data->persetujuan_dua) && !empty($data->persetujuan_dua) ? $data->persetujuan_dua : '',
            2 => isset($data->persetujuan_tiga) && !empty($data->persetujuan_tiga) ? $data->persetujuan_tiga : '',
        ];
        $arr_approval_level = json_encode($arr_approval_level);
        return $arr_approval_level;
    }
    /**
     * @param StoreRequest $request
     * @return array
     */
    public function saveAdditionalData($data)
    {
        $arr_data = [
            "karyawan_khusus" => isset($data->karyawan_khusus) ? ($data->karyawan_khusus == 1 || $data->karyawan_khusus == 'on' ? '1' : '0') : '0',
            "notifikasi_instant" => isset($data->notifikasi_instant) ? ($data->notifikasi_instant == 1 || $data->notifikasi_instant == 'on' ? '1' : '0') : '0',
            "edit_lat_long" => isset($data->edit_lat_long) ? ($data->edit_lat_long == 1 || $data->edit_lat_long == 'on' ? '1' : '0') : '0',
            // "manage_task" => isset($data->atur_tugas) ? '1' : '0',
            // "manage_notice" => isset($data->menambahkan_pengumuman) ? '1' : '0',
            // "manage_project" => isset($data->atur_project) ? '1' : '0',
            "report_task" => isset($data->report_task) ? ($data->report_task == 1 || $data->report_task == 'on' ? '1' : '0') : '0',
            "jangan_lacak_saya" => isset($data->jangan_lacak_saya) ? ($data->jangan_lacak_saya == 1 || $data->jangan_lacak_saya == 'on' ? '1' : '0') : '0',

            // tugas
            "list_tugas" => isset($data->list_tugas) ? ($data->list_tugas == 1 || $data->list_tugas == 'on' ? '1' : '0') : '0',
            "create_tugas" => isset($data->create_tugas) ? ($data->create_tugas == 1 || $data->create_tugas == 'on' ? '1' : '0') : '0',
            "edit_tugas" => isset($data->edit_tugas) ? ($data->edit_tugas == 1 || $data->edit_tugas == 'on' ? '1' : '0') : '0',
            "delete_tugas" => isset($data->delete_tugas) ? ($data->delete_tugas == 1 || $data->delete_tugas == 'on' ? '1' : '0') : '0',

            // proyek
            "list_proyek" => isset($data->list_proyek) ? ($data->list_proyek == 1 || $data->list_proyek == 'on' ? '1' : '0') : '0',
            "create_proyek" => isset($data->create_proyek) ? ($data->create_proyek == 1 || $data->create_proyek == 'on' ? '1' : '0') : '0',
            "edit_proyek" => isset($data->edit_proyek) ? ($data->edit_proyek == 1 || $data->edit_proyek == 'on' ? '1' : '0') : '0',
            "delete_proyek" => isset($data->delete_proyek) ? ($data->delete_proyek == 1 || $data->delete_proyek == 'on' ? '1' : '0') : '0',

            // pengumuman
            "list_pengumuman" => isset($data->list_pengumuman) ? ($data->list_pengumuman == 1 || $data->list_pengumuman == 'on' ? '1' : '0') : '0',
            "create_pengumuman" => isset($data->create_pengumuman) ? ($data->create_pengumuman == 1 || $data->create_pengumuman == 'on' ? '1' : '0') : '0',
            "edit_pengumuman" => isset($data->edit_pengumuman) ? ($data->edit_pengumuman == 1 || $data->edit_pengumuman == 'on' ? '1' : '0') : '0',
            "delete_pengumuman" => isset($data->delete_pengumuman) ? ($data->delete_pengumuman == 1 || $data->delete_pengumuman == 'on' ? '1' : '0') : '0',

            // masalah
            "list_ticket" => isset($data->list_ticket) ? ($data->list_ticket == 1 || $data->list_ticket == 'on' ? '1' : '0') : '0',
            "create_ticket" => isset($data->create_ticket) ? ($data->create_ticket == 1 || $data->create_ticket == 'on' ? '1' : '0') : '0',
            "edit_ticket" => isset($data->edit_ticket) ? ($data->edit_ticket == 1 || $data->edit_ticket == 'on' ? '1' : '0') : '0',
            "delete_ticket" => isset($data->delete_ticket) ? ($data->delete_ticket == 1 || $data->delete_ticket == 'on' ? '1' : '0') : '0',
            "reply_ticket" => isset($data->reply_ticket) ? ($data->reply_ticket == 1 || $data->reply_ticket == 'on' ? '1' : '0') : '0',

            //Melihat semua proyek
            "see_all_project" => isset($data->see_all_project) ? ($data->see_all_project == 1 || $data->see_all_project == 'on' ? '1' : '0') : '0',

            // is_required_absence
            "is_required_absence" => isset($data->is_required_absence) ? ($data->is_required_absence == 1 || $data->is_required_absence == 'on' ? '1' : '0') : '0',
            
            // pengaturan kapal
            "is_nahkoda" => isset($data->is_nahkoda) ? ($data->is_nahkoda == 1 || $data->is_nahkoda == 'on' ? '1' : '0') : '0',
            "is_admin" => isset($data->is_admin) ? ($data->is_admin == 1 || $data->is_admin == 'on' ? '1' : '0') : '0',
            "is_spv_pembelian" => isset($data->is_spv_pembelian) ? ($data->is_spv_pembelian == 1 || $data->is_spv_pembelian == 'on' ? '1' : '0') : '0',
            "is_manager" => isset($data->is_manager) ? ($data->is_manager == 1 || $data->is_manager == 'on' ? '1' : '0') : '0',
        ];
        $arr_data = json_encode($arr_data);
        return $arr_data;
    }
}
