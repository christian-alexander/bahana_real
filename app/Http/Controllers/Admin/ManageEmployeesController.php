<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\ClusterWorkingHour;
use App\DataTables\Admin\EmployeesDataTable;
use App\Designation;
use App\EmployeeDetails;
use App\EmployeeDocs;
use App\EmployeeSkill;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Admin\Employee\StoreRequest;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\Http\Requests\Admin\Employee\ImportPayment;
use App\Imports\EmployeeImport;
use App\Leave;
use App\LeaveType;
use App\Project;
use App\ProjectMember;
use App\ProjectTimeLog;
use App\Role;
use App\RoleUser;
use App\Skill;
use App\SubCompany;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\UniversalSearch;
use App\User;
use App\UserActivity;
use App\Wilayah;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Office;


class ManageEmployeesController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.employees';
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
    public function index(EmployeesDataTable $dataTable)
    {
        $this->employees = User::allEmployees();
        $this->skills = Skill::all();
        $this->departments = Team::all();
        $this->designations = Designation::all();
        $this->cabangs = Cabang::all();
        $this->subcompanys = SubCompany::all();
        $this->wilayahs = Wilayah::all();
        $this->totalEmployees = count($this->employees);
        $this->roles = Role::where('roles.name', '<>', 'client')->get();
        $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->select('users.*')
            ->groupBy('project_members.user_id')
            ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
            ->orderBy('users.id')
            ->get();

        $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name')->whereNotIn('users.id', function ($query) {

                $query->select('user_id as id')->from('project_members');
            })
            ->where('roles.name', '<>', 'client')
            ->get();

        $this->freeEmployees = $whoseProjectCompleted->merge($notAssignedProject)->count();

        // return view('admin.employees.index', $this->data);
        return $dataTable->render('admin.employees.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = new EmployeeDetails();
        $this->fields = $employee->getCustomFieldGroupsWithFields()->fields;
        $this->skills = Skill::all()->pluck('name')->toArray();
        $this->teams = Team::all();
        $this->designations = Designation::all();
        $this->cabangs = Cabang::all();
        $this->subcompanys = SubCompany::all();
        $this->wilayahs = Wilayah::all();
        $this->modules = \Auth::user()->modules;
        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();
        $this->cluster_working_hours = ClusterWorkingHour::all();
        $this->office = Office::where('is_kapal', 1)->get();

        return view('admin.employees.create', $this->data);
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
            "akses_delegasi" => isset($data->akses_delegasi) ? ($data->akses_delegasi == 1 || $data->akses_delegasi == 'on' ? '1' : '0') : '0',

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
    public function store(StoreRequest $request)
    {
        // return $request->all();
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
                    // set option_employee_subcompany
                    $employee->option_employee_sub_company = json_encode($request->option_employee_subcompany);
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
              
              	if (isset($request->is_abk) && !empty($request->is_abk)) {
                    if($request->is_abk == 1 || $request->is_abk == 'on'){
                      $employee->is_abk = 1;
                    }
                    else{
                      $employee->is_abk = 0;
                      
                    }
                }

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

                if (isset($request->is_pe) && !empty($request->is_pe)) {
                    if($request->is_pe == 1 || $request->is_pe == 'on'){
                      $employee->is_pe = 1;
                    }
                    else{
                      $employee->is_pe = 0;
                      
                    }
                }
                if (isset($request->is_atasan) && !empty($request->is_atasan)) {
                    if($request->is_atasan == 1 || $request->is_atasan == 'on'){
                        $employee->is_atasan = 1;
                      }
                      else{
                        $employee->is_atasan = 0;
                        
                      }
                }
                if (isset($request->user_orang_kepercayaan) && !empty($request->user_orang_kepercayaan)) {
                    $employee->user_orang_kepercayaan = json_encode($request->user_orang_kepercayaan);
                }
                if (isset($request->sub_company_orang_kepercayaan) && !empty($request->sub_company_orang_kepercayaan)) {
                    $arr_temp=[];
                    $arr_temp_status=[];
                    $idx=0;
                    foreach ($request->sub_company_orang_kepercayaan as $val) {
                        $arr_temp[$request->user_orang_kepercayaan[$idx]] = $val;
                        $arr_temp_status[$request->user_orang_kepercayaan[$idx]] = isset($request->active[$idx]) ? ($request->active[$idx][0] == 1 || $request->active[$idx][0] == 'on' ? '1' : '0') : '0';
                        
                        $idx++;
                    }
                    $employee->sub_company_orang_kepercayaan = json_encode($arr_temp);
                    $employee->is_on_orang_kepercayaan = json_encode($arr_temp_status);
                }
                // $employee->is_on_orang_kepercayaan = isset($request->is_on_orang_kepercayaan) ? ($request->is_on_orang_kepercayaan == 1 || $request->is_on_orang_kepercayaan == 'on' ? '1' : '0') : '0';

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

        return Reply::redirect(route('admin.employees.index'), __('messages.employeeAdded'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $this->leave_remaining = 0;
        $this->leave_taken = 0;

        $leaveTaken = Leave::leaveTaken($id);
        if ($leaveTaken['status']==200) {
            $this->leave_taken = $leaveTaken['data'];
        }
        // dd($this->leave_taken);
        
        return view('admin.employees.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->skills = Skill::all()->pluck('name')->toArray();
        $this->teams = Team::all();
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

        $option_employee_sub_company = json_decode($this->employeeDetail->option_employee_sub_company);
        $this->option_employee_sub_company = isset($option_employee_sub_company) ? $option_employee_sub_company : [];

        $this->cluster_working_hours = ClusterWorkingHour::all();
        $this->office = Office::where('is_kapal', 1)->get();

        $this->user_orang_kepercayaan = json_decode($this->employeeDetail->user_orang_kepercayaan);
        // dd($this->user_orang_kepercayaan);
        $this->sub_company_orang_kepercayaan = json_decode($this->employeeDetail->sub_company_orang_kepercayaan, true);
        
        $this->active = json_decode($this->employeeDetail->is_on_orang_kepercayaan, true);

        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();

        return view('admin.employees.edit', $this->data);
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
            $user->image = Files::upload($request->image, 'avatar', 300);
        }
      
      	// for admin office
        $user->is_admin_office = ($request->input('is_admin_office') == 'on') ? '1' : '0';
      	$admin_role = RoleUser::where('role_id', '=', 1)->where('user_id', '=', $id);
      	//dd($admin_role->exists());
      
      	if($user->is_admin_office == '1' && !$admin_role->exists() ){
      		$user->roles()->attach(1); // attach role app admin
        }else if($user->is_admin_office == '0' && $admin_role->exists() ){
          	$admin_role->delete();
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
            // set option_employee_sub_company
            $employee->option_employee_sub_company = json_encode($request->option_employee_subcompany);
        } else {
            $employee->option_employee = '0';
            // set option_employee_sub_company
            $employee->option_employee_sub_company = '[]';
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
      
      	if (isset($request->is_abk) && !empty($request->is_abk)) {
            if($request->is_abk == 1 || $request->is_abk == 'on'){
              $employee->is_abk = 1;
            }
            else{
              $employee->is_abk = 0;

            }
        }
      	else{
              $employee->is_abk = 0;
          
        }

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
            }else{
              $employee->is_pc = 0;
            }
        }else{
              $employee->is_pc = 0;
        }

        if (isset($request->is_pe) && !empty($request->is_pe)) {
            if($request->is_pe == 1 || $request->is_pe == 'on'){
              $employee->is_pe = 1;
            }else{
              $employee->is_pe = 0;
            }
        }else{
              $employee->is_pe = 0;
        }

        if (isset($request->is_atasan) && !empty($request->is_atasan)) {
            if($request->is_atasan == 1 || $request->is_atasan == 'on'){
                $employee->is_atasan = 1;
              }
              else{
                $employee->is_atasan = 0;
              }
        }

        // if (isset($request->user_orang_kepercayaan) && !empty($request->user_orang_kepercayaan)) {
        //     $employee->user_orang_kepercayaan = json_encode($request->user_orang_kepercayaan);
        // }else{
        //     $employee->user_orang_kepercayaan = null;
        // }
        // if (isset($request->sub_company_orang_kepercayaan) && !empty($request->sub_company_orang_kepercayaan)) {
        //     $employee->sub_company_orang_kepercayaan = json_encode($request->sub_company_orang_kepercayaan);
        // }else{
        //     $employee->sub_company_orang_kepercayaan = null;
        // }
        // $employee->is_on_orang_kepercayaan = isset($request->is_on_orang_kepercayaan) ? ($request->is_on_orang_kepercayaan == 1 || $request->is_on_orang_kepercayaan == 'on' ? '1' : '0') : '0';
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

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $employee->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('admin.employees.index'), __('messages.employeeUpdated'));
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

        $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'employee')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        User::destroy($id);
        return Reply::success(__('messages.employeeDeleted'));
    }

    public function tasks($userId, $hideCompleted)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.status', 'tasks.project_id', 'taskboard_columns.column_name', 'taskboard_columns.label_color')
            ->where('task_users.user_id', $userId);

        if ($hideCompleted == '1') {
            $tasks->where('tasks.board_column_id', $taskBoardColumn->id);
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
                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';

                if ($row->is_private) {
                    $name .= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                }
                return $name;
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })
            ->editColumn('project_name', function ($row) {
                if (!is_null($row->project_name)) {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
                }
            })
            ->rawColumns(['column_name', 'project_name', 'due_date', 'heading'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function timeLogs($userId)
    {
        $timeLogs = ProjectTimeLog::join('projects', 'projects.id', '=', 'project_time_logs.project_id')
            ->select('project_time_logs.id', 'projects.project_name', 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.memo', 'project_time_logs.project_id', 'project_time_logs.total_minutes')
            ->where('project_time_logs.user_id', $userId);
        $timeLogs->get();

        return DataTables::of($timeLogs)
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                if (!is_null($row->end_time)) {
                    return $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
                } else {
                    return "<label class='label label-success'>Active</label>";
                }
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';

                if (($row->total_minutes % 60) > 0) {
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                }

                return $timeLog;
            })
            ->rawColumns(['end_time', 'project_name'])
            ->removeColumn('project_id')
            ->make(true);
    }

    public function export($status, $employee, $role)
    {
        if ($role != 'all' && $role != '') {
            $userRoles = Role::findOrFail($role);
        }
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
                'users.created_at',
                'roles.name as roleName'
            );
        if ($status != 'all' && $status != '') {
            $rows = $rows->where('users.status', $status);
        }

        if ($employee != 'all' && $employee != '') {
            $rows = $rows->where('users.id', $employee);
        }

        if ($role != 'all' && $role != '' && $userRoles) {
            if ($userRoles->name == 'admin') {
                $rows = $rows->where('roles.id', $role);
            } elseif ($userRoles->name == 'employee') {
                $rows =  $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role)
                    ->having('roleName', '<>', 'admin');
            } else {
                $rows = $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role);
            }
        }
        $attributes =  ['roleName'];
        $rows = $rows->groupBy('users.id')->get()->makeHidden($attributes);

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
        $roleId = $request->role;
        $employeeRole = Role::where('name', 'employee')->first();
        $user = User::findOrFail($userId);

        RoleUser::where('user_id', $user->id)->delete();
        $user->roles()->attach($employeeRole->id);
        if ($employeeRole->id != $roleId) {
            $user->roles()->attach($roleId);
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
        return view('admin.employees.docs-create', $this->data);
    }

    public function freeEmployees()
    {
        if (\request()->ajax()) {

            $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->select('users.*')
                ->groupBy('project_members.user_id')
                ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
                ->orderBy('users.id')
                ->get();

            $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->whereNotIn('users.id', function ($query) {

                    $query->select('user_id as id')->from('project_members');
                })
                ->where('roles.name', '<>', 'client')
                ->get();

            $freeEmployees = $whoseProjectCompleted->merge($notAssignedProject);

            return DataTables::of($freeEmployees)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.employees.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="' . route('admin.employees.show', [$row->id]) . '" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="View Employee Details"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                })
                ->editColumn(
                    'created_at',
                    function ($row) {
                        return Carbon::parse($row->created_at)->format($this->global->date_format);
                    }
                )
                ->editColumn(
                    'status',
                    function ($row) {
                        if ($row->status == 'active') {
                            return '<label class="label label-success">' . __('app.active') . '</label>';
                        } else {
                            return '<label class="label label-danger">' . __('app.inactive') . '</label>';
                        }
                    }
                )
                ->editColumn('name', function ($row) {
                    $image = '<img src="' . $row->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
                    return '<a href="' . route('admin.employees.show', $row->id) . '">' . $image . ' ' . ucwords($row->name) . '</a>';
                })
                ->rawColumns(['name', 'action', 'role', 'status'])
                ->removeColumn('roleId')
                ->removeColumn('roleName')
                ->removeColumn('current_role')
                ->make(true);
        }

        return view('admin.employees.free_employees', $this->data);
    }
    public function importExcel(ImportPayment $request)
    {
        \DB::beginTransaction();
        try {
            if ($request->hasFile('import_file')) {
                $path = $request->file('import_file');
                // $path = $request->file('import_file')->getRealPath();
                $import = new EmployeeImport();
                Excel::import($import, $path);
                if (isset($import->resp) && !empty($import->resp)) {
                    \DB::rollback();
                    throw new \Exception($import->resp);
                }
                \DB::commit();
                return Reply::redirect(route('admin.employees.index'), __('messages.importSuccess'));
            }
            throw new \Exception("Tidak ada file yang di upload");
        } catch (\Throwable $e) {
            \DB::rollback();
            return Reply::error($e->getMessage());
        }
    }
    public function downloadSample()
    {
        return response()->download(public_path() . '/sample/form-import-employee-bahana.xlsx');
    }
}
