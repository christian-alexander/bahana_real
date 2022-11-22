<?php

namespace App\Http\Controllers\Member;

use App\Cabang;
use App\ClusterWorkingHour;
use App\Designation;
use App\EmployeeDetails;
use App\EmployeeDocs;
use App\EmployeeSkill;
use App\Helper\Reply;
use App\Http\Requests\Member\Employee\StoreRequest;
use App\Http\Requests\Member\Employee\UpdateRequest;
use App\Http\Requests\Member\User\StoreUser;
use App\Http\Requests\Member\User\UpdateEmployee;
use App\Leave;
use App\LeaveType;
use App\Notifications\NewUser;
use App\Office;
use App\Project;
use App\ProjectTimeLog;
use App\Role;
use App\Skill;
use App\SubCompany;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\User;
use App\UserActivity;
use App\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MemberABKController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'ABK';
        $this->pageIcon = 'icon-user';
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
        //if (!$this->user->can('view_employees')) {
            //abort(403);
        //}

        $this->skills = Skill::all();
        //$this->employees = User::allEmployees();
        
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->get();
        $this->employees = $users;
        return view('member.abk.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd($this->user->employeeDetail);
        if (!$this->user->employeeDetail->is_hrd_kapal==1 && !$this->user->employeeDetail->is_pc == 1){
            abort(403);
        }
        $this->skills = Skill::all()->pluck('name')->toArray();
        $employee = new EmployeeDetails();
        $this->subcompanys = SubCompany::all();
        $this->cabangs = Cabang::all();
        $this->wilayahs = Wilayah::all();
        $this->teams  = Team::all();
        $this->designations = Designation::all();
        $this->fields = $employee->getCustomFieldGroupsWithFields()->fields;
        $this->cluster_working_hours = ClusterWorkingHour::all();
        $this->office = Office::where('is_kapal', 1)->get();
        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();
        return view('member.abk.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {

        $company = company();

        if (!is_null($company->employees) && $company->employees->count() >= $company->package->max_employees) {
            return Reply::error(__('messages.upgradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
        }

        if (!is_null($company->employees) && $company->package->max_employees < $company->employees->count()) {
            return Reply::error(__('messages.downGradePackageForAddEmployees', ['employeeCount' => company()->employees->count(), 'maxEmployees' => $company->package->max_employees]));
        }
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->mobile = $request->input('mobile');
            $user->gender = $request->input('gender');
            $user->login = $request->login;

            if ($request->hasFile('image')) {
                $user->image = Files::upload($request->image, 'avatar', 300);
            }

            $user->save();

            $tags = json_decode($request->tags);
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    // check or store skills
                    $skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

                    // Store user skills
                    $skill = new EmployeeSkill();
                    $skill->user_id = $user->id;
                    $skill->skill_id = $skillData->id;
                    $skill->save();
                }
            }

            if ($user->id) {
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
                $employee->employee_id = $request->employee_id;
                $employee->address = $request->address;
                $employee->hourly_rate = $request->hourly_rate;
                $employee->slack_username = $request->slack_username;
                if (isset($request->joining_date) && !empty($request->joining_date)) {
                    $setJoinDate = Carbon::createFromFormat($this->global->date_format, $request->joining_date)->format('Y-m-d');
                } else {
                    $setJoinDate = Carbon::now();
                }
                $employee->joining_date = $setJoinDate;
                // di default hari ini
                $employee->last_date = Carbon::now()->format('Y-m-d');
                // if ($request->last_date != '') {
                // $employee->last_date = Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d');
                // }
                $employee->department_id = $request->department;
                $employee->designation_id = $request->designation;
                $employee->cabang_id = $request->cabang;
                $employee->sub_company_id = $request->subcompany;
                $employee->wilayah_id = $request->wilayah;

                $employee->permission = $this->savePermissionEmployee($request);

                $employee->permission_require = $this->saveApprovalLevel($request);

                if (isset($request->option_employee) && !empty($request->option_employee)) {
                    $employee->option_employee = '1';
                }
                if (isset($request->latitude) && !empty($request->latitude)) {
                    $employee->latitude = $request->latitude;
                }
                if (isset($request->longitude) && !empty($request->longitude)) {
                    $employee->longitude = $request->longitude;
                }

                // store to additional field (additional_field)
                $employee->additional_field = $this->saveAdditionalData($request);

                // get data cluster
                $getCluster = ClusterWorkingHour::find($request->cluster_working_hour);
                $employee->cluster_working_hour_id = $getCluster->id;


                $employee->office_start_time = '-';
                $employee->office_end_time = '-';
              	
                if (isset($request->office_id) && !empty($request->office_id)) {
                    $employee->office_id = $request->office_id;
                }

                $employee->is_abk = 1;

                if (isset($request->is_hrd_kapal) && !empty($request->is_hrd_kapal)) {
                    if($request->is_hrd_kapal == 1 || $request->is_hrd_kapal == 'on'){
                      $employee->is_hrd_kapal = 1;
                    }
                    else{
                      $employee->is_hrd_kapal = 0;
                      
                    }
                }

                if (isset($request->is_pc) && !empty($request->is_pc)) {
                    if($request->is_pc == 1 || $request->is_pc == 'on'){
                      $employee->is_pc = 1;
                    }
                    else{
                      $employee->is_pc = 0;
                      
                    }
                }
                $employee->save();
            }

            // To add custom fields data
            if ($request->get('custom_fields_data')) {
                $employee->updateCustomFieldData($request->get('custom_fields_data'));
            }


            $role = Role::where('name', 'employee')->first();
            $user->attachRole($role->id);
            DB::commit();
        } catch (\Swift_TransportException $e) {
            // Rollback Transaction
            DB::rollback();
            return Reply::error('Please configure SMTP details to add employee. Visit Settings -> Email setting to set SMTP', 'smtp_error');
        } catch (\Exception $e) {
            // Rollback Transaction
            DB::rollback();
            return Reply::error($e);
            return Reply::error('Some error occured when inserting the data. Please try again or contact support');
        }
        $this->logSearchEntry($user->id, $user->name, 'admin.employees.show', 'employee');
        return Reply::redirect(route('member.abk.index'), __('messages.employeeAdded'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$this->user->employeeDetail->is_hrd_kapal==1 && !$this->user->employeeDetail->is_pc == 1){
            abort(403);
        }
        $this->employee = User::with(['employeeDetail', 'employeeDetail.designation', 'employeeDetail.cabang', 'employeeDetail.department'])->withoutGlobalScope('active')->findOrFail($id);
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->employee->id)->first();
        $this->employeeDocs = EmployeeDocs::where('user_id', '=', $this->employee->id)->get();

        if (!is_null($this->employeeDetail)) {
            $this->employeeDetail = $this->employeeDetail->withCustomFields();
            $this->fields = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $completedTaskColumn = TaskboardColumn::where('slug', 'completed')->first();

        $this->taskCompleted = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $id)
            ->where('tasks.board_column_id', $completedTaskColumn->id)
            ->count();

        $hoursLogged = ProjectTimeLog::where('user_id', $id)->sum('total_minutes');

        $timeLog = intdiv($hoursLogged, 60) . ' hrs ';

        if (($hoursLogged % 60) > 0) {
            $timeLog .= ($hoursLogged % 60) . ' mins';
        }

        $this->hoursLogged = $timeLog;

        $this->activities = UserActivity::where('user_id', $id)->orderBy('id', 'desc')->get();
        $this->projects = Project::select('projects.id', 'projects.project_name', 'projects.deadline', 'projects.completion_percent')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $id)
            ->get();
        $this->leaves = Leave::byUser($id);
        $this->leavesCount = Leave::byUserCount($id);

        $this->leaveTypes = LeaveType::byUser($id);
        $this->allowedLeaves = LeaveType::sum('no_of_leaves');
        $this->listEmployee = User::allEmployees();
        $approvalLevel = json_decode($this->employeeDetail->permission_require);
        $this->approvalLevel = isset($approvalLevel) ? $approvalLevel : ["", "", ""];
        $arr_approval = [];
        foreach ($this->approvalLevel as $item) {
            if ($item != '') {
                // $item = User::find($item);
                array_push($arr_approval, User::find($item)->name);
            } else {
                array_push($arr_approval, '');
            }
        }
        $this->arr_approval = $arr_approval;
        return view('member.abk.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!$this->user->employeeDetail->is_hrd_kapal==1 && !$this->user->employeeDetail->is_pc == 1){
            abort(403);
        }

        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->skills = Skill::all()->pluck('name')->toArray();
        $this->teams = Team::where('parent_id', null)->get();
        $this->designations = Designation::all();
        $this->cabangs = Cabang::all();
        $this->subcompanys = SubCompany::all();
        $this->wilayahs = Wilayah::all();
        $this->modules = \Auth::user()->modules;
        if (!is_null($this->employeeDetail)) {
            $this->employeeDetail = $this->employeeDetail->withCustomFields();
            $this->fields = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        }
        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();
        $approvalLevel = json_decode($this->employeeDetail->permission_require);
        $this->approvalLevel = isset($approvalLevel) ? $approvalLevel : ["", "", ""];

        $additional_field = json_decode($this->employeeDetail->additional_field);
        $this->additional_field = isset($additional_field) ? $additional_field : [];

        $this->cluster_working_hours = ClusterWorkingHour::all();
        $this->office = Office::where('is_kapal', 1)->get();

        return view('member.abk.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->mobile = $request->input('mobile');
        $user->gender = $request->input('gender');
        $user->status = $request->input('status');
        $user->login = $request->login;

        if ($request->hasFile('image')) {
            $user->image = Files::uploadLocalOrS3($request->image, 'avatar', 300);
        }

        $user->save();

        $tags = json_decode($request->tags);
        if (!empty($tags)) {
            EmployeeSkill::where('user_id', $user->id)->delete();
            foreach ($tags as $tag) {
                // check or store skills
                $skillData = Skill::firstOrCreate(['name' => strtolower($tag->value)]);

                // Store user skills
                $skill = new EmployeeSkill();
                $skill->user_id = $user->id;
                $skill->skill_id = $skillData->id;
                $skill->save();
            }
        }


        $employee = EmployeeDetails::where('user_id', '=', $user->id)->first();
        if (empty($employee)) {
            $employee = new EmployeeDetails();
            $employee->user_id = $user->id;
        }
        $employee->employee_id = $request->employee_id;
        $employee->address = $request->address;
        if (isset($request->hourly_rate) && !empty($request->hourly_rate)) {
            $employee->hourly_rate = $request->hourly_rate;
        }
        if (isset($request->slack_username) && !empty($request->slack_username)) {
            $employee->slack_username = $request->slack_username;
        }
        if (isset($request->joining_date) && !empty($request->joining_date)) {
            $employee->joining_date = Carbon::createFromFormat($this->global->date_format, $request->joining_date)->format('Y-m-d');
        }

        // $employee->last_date = null;

        // tidak perlu di update karena waktu store udah di default hari pas store
        // if ($request->last_date != '') {
        //     $employee->last_date = Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d');
        // }

        $employee->department_id = $request->department;
        $employee->designation_id = $request->designation;
        $employee->cabang_id = $request->cabang;
        $employee->sub_company_id = $request->subcompany;
        $employee->wilayah_id = $request->wilayah;
        $employee->permission = $this->savePermissionEmployee($request);
        $employee->permission_require = $this->saveApprovalLevel($request);
        if (isset($request->option_employee) && !empty($request->option_employee)) {
            $employee->option_employee = '1';
        } else {
            $employee->option_employee = '0';
        }
        if (isset($request->latitude) && !empty($request->latitude)) {
            $employee->latitude = $request->latitude;
        }
        if (isset($request->longitude) && !empty($request->longitude)) {
            $employee->longitude = $request->longitude;
        }
        // store to additional field (additional_field)
        $employee->additional_field = $this->saveAdditionalData($request);

        // get data cluster
        $getCluster = ClusterWorkingHour::find($request->cluster_working_hour);
        $employee->cluster_working_hour_id = $getCluster->id;


        $employee->office_start_time = '-';
        $employee->office_end_time = '-';
              	
        if (isset($request->office_id) && !empty($request->office_id)) {
            $employee->office_id = $request->office_id;
        }
        $employee->is_abk = 1;

        if (isset($request->is_hrd_kapal) && !empty($request->is_hrd_kapal)) {
            if($request->is_hrd_kapal == 1 || $request->is_hrd_kapal == 'on'){
              $employee->is_hrd_kapal = 1;
            }
            else{
              $employee->is_hrd_kapal = 0;

            }
        }
      	else{
              $employee->is_hrd_kapal = 0;
          
        }

        if (isset($request->is_pc) && !empty($request->is_pc)) {
            if($request->is_pc == 1 || $request->is_pc == 'on'){
              $employee->is_pc = 1;
            }
            else{
              $employee->is_pc = 0;

            }
        }
      	else{
              $employee->is_pc = 0;
          
        }


        $employee->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $employee->updateCustomFieldData($request->get('custom_fields_data'));
        }
        return Reply::redirect(route('member.abk.index'), 'Berhasil mengubah ABK');
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
    public function saveAdditionalData($data)
    {
        $arr_data = [
            "karyawan_khusus" => isset($data->karyawan_khusus) ? ($data->karyawan_khusus == 1 || $data->karyawan_khusus == 'on' ? '1' : '0') : '0',
            "edit_lat_long" => isset($data->edit_lat_long) ? ($data->edit_lat_long == 1 || $data->edit_lat_long == 'on' ? '1' : '0') : '0',
            // "manage_task" => isset($data->atur_tugas) ? '1' : '0',
            // "manage_notice" => isset($data->menambahkan_pengumuman) ? '1' : '0',
            // "manage_project" => isset($data->atur_project) ? '1' : '0',
            "report_task" => isset($data->report_task) ? ($data->report_task == 1 || $data->report_task == 'on' ? '1' : '0') : '0',

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
        ];
        $arr_data = json_encode($arr_data);
        return $arr_data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);

        if ($user->id == 1) {
            return Reply::error(__('messages.adminCannotDelete'));
        }

        User::destroy($id);
        return Reply::success(__('messages.employeeDeleted'));
    }

    public function data(Request $request)
    {
        $userIdAbk = EmployeeDetails::where("is_abk",1)->pluck("user_id");
		// print_r($userIdAbk);
      	// exit();
        $users = User::leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->whereIn('users.id', $userIdAbk);

        // if ($request->employee != 'all' && $request->employee != '') {
        //     $users = $users->where('users.id', $request->employee);
        // }

        // if ($request->skill != 'all' && $request->skill != '' && $request->skill != null && $request->skill != 'null') {
        //     $users =  $users->join('employee_skills', 'employee_skills.user_id', '=', 'users.id')
        //         ->whereIn('employee_skills.skill_id', explode(',', $request->skill));
        // }

        $users = $users->get();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->employeeDetail->is_hrd_kapal==1 || $this->user->employeeDetail->is_pc == 1) {
                    $action .= ' <a href="' . route('member.abk.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                        data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    $action .= ' <a href="' . route('member.abk.show', [$row->id]) . '" class="btn btn-success btn-circle"
                        data-toggle="tooltip" data-original-title="View Employee Details"><i class="fa fa-search" aria-hidden="true"></i></a>';
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                        data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }

                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->editColumn('name', function ($row) {
                if ($row->hasRole('admin')) {
                    return '<a href="' . route('member.abk.show', $row->id) . '">' . ucwords($row->name) . '</a><br> <label class="label label-danger">admin</label>';
                }
                if ($row->hasRole('project_admin')) {
                    return '<a href="' . route('member.abk.show', $row->id) . '">' . ucwords($row->name) . '</a><br> <label class="label label-info">project admin</label>';
                }
                return '<a href="' . route('member.abk.show', $row->id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('email', function ($row) {
                return $row->email;
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    public function tasks($userId, $hideCompleted)
    {
        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.status', 'tasks.project_id')
            ->where('task_users.user_id', $userId);

        if ($hideCompleted == '1') {
            $tasks->where('tasks.status', '=', 'incomplete');
        }

        $tasks->get();

        return DataTables::of($tasks)
            ->editColumn('due_date', function ($row) {
                if ($row->due_date->isPast()) {
                    return '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>';
                }
                return '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('heading', function ($row) {
                return ucfirst($row->heading);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'incomplete') {
                    return '<label class="label label-danger">Incomplete</label>';
                }
                return '<label class="label label-success">Completed</label>';
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->rawColumns(['status', 'project_name', 'due_date'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function timeLogs($userId)
    {
        $timeLogs = ProjectTimeLog::join('projects', 'projects.id', '=', 'project_time_logs.project_id')
            ->select('project_time_logs.id', 'projects.project_name', 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.memo', 'project_time_logs.project_id')
            ->where('project_time_logs.user_id', $userId);
        $timeLogs->get();

        return DataTables::of($timeLogs)
            ->editColumn('start_time', function ($row) {
                return $row->start_time->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>Active</label>";
                }
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->rawColumns(['end_time', 'project_name'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function export()
    {
        $rows = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->withoutGlobalScope('active')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')

            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'designations.name as designation_name',
                'employee_details.address',
                'employee_details.hourly_rate',
                'users.created_at'
            )
            ->groupBy('users.id')
            ->get();

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Designation', 'Address', 'Hourly Rate', 'Created at', 'Role'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = [
                "id" => $row->id,
                "name" => $row->name,
                "email" => $row->email,
                "mobile" => $row->mobile,
                "Designation" => $row->designation_name,
                "address" => $row->address,
                "hourly_rate" => $row->hourly_rate,
                "created_at" => $row->created_at->format('Y-m-d h:i:s a'),
                "roleName" => $row->roleName
            ];
        }

        // Generate and return the spreadsheet
        Excel::create('Employees', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Employees');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Employees file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });
            });
        })->download('xlsx');
    }

    public function assignRole(Request $request)
    {
        $userId = $request->userId;
        $roleName = $request->role;
        $adminRole = Role::where('name', 'admin')->first();
        $projectAdminRole = Role::where('name', 'project_admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();
        $user = User::findOrFail($userId);

        switch ($roleName) {
            case "admin":
                $user->detachRoles($user->roles);
                $user->roles()->attach($adminRole->id);
                $user->roles()->attach($employeeRole->id);
                break;

            case "project_admin":
                $user->detachRoles($user->roles);
                $user->roles()->attach($projectAdminRole->id);
                $user->roles()->attach($employeeRole->id);
                break;

            case "none":
                $user->detachRoles($user->roles);
                $user->roles()->attach($employeeRole->id);
                break;
        }
        return Reply::success(__('messages.roleAssigned'));
    }

    public function assignProjectAdmin(Request $request)
    {
        $userId = $request->userId;
        $projectId = $request->projectId;
        $project = Project::findOrFail($projectId);
        $project->project_admin = $userId;
        $project->save();

        return Reply::success(__('messages.roleAssigned'));
    }

    public function docsCreate(Request $request, $id)
    {
        $this->employeeID = $id;
        return view('member.abk.docs-create', $this->data);
    }
}

