<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ScheduleKapal\StoreRequest;
use App\Http\Requests\ScheduleKapal\UpdateRequest;
use App\Office;
use App\OfficeWifi;
use App\ScheduleKapal;
use App\User;
use Carbon\Carbon;

class AdminScheduleKapalController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = "Schedule Kapal";
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
        // return 'asd';
        $this->office = ScheduleKapal::leftJoin('users', 'users.id', 'schedule_kapal.user_id')->leftJoin('office', 'office.id', 'schedule_kapal.kapal_id')->select('schedule_kapal.*', 'users.name as user_id', 'office.name as kapal_id')->get();
        return view('admin.schedulekapal.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->kapal = Office::where("is_kapal",1)->get();
		$this->listEmployee = User::withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->join('employee_details','employee_details.user_id','=','users.id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->where('employee_details.is_abk', '=', '1')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();
        return view('admin.schedulekapal.create', $this->data);
    }


    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $office = new ScheduleKapal();
        // generate code
        $office->date_start = Carbon::createFromFormat($this->global->date_format, $request->date_start)->format('Y-m-d');
        $office->date_end = Carbon::createFromFormat($this->global->date_format, $request->date_end)->format('Y-m-d');
        $office->user_id = $request->user_id;
        $office->kapal_id = $request->kapal_id;
        $office->created_by = \Auth::user()->id;
        //$office->latitude = $request->latitude;
        //$office->longitude = $request->longitude;
        //$office->radius = $request->radius;
        //$office->jam_istirahat_awal = $request->jam_istirahat_awal;
        //$office->jam_istirahat_akhir = $request->jam_istirahat_akhir;
        $office->save();

        return Reply::redirect(route('admin.schedulekapal.index'), 'Kapal created successfully.');
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
        $this->schedulekapal = ScheduleKapal::findOrFail($id);

        $this->kapal = Office::where("is_kapal",1)->get();
        $this->listEmployee = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', '<>', 'client')
            ->groupBy('users.id')
            ->orderBy('users.name', 'ASC')
            ->get();
        // get wifi
        $this->getWifi = OfficeWifi::where('office_id', $id)->get();
        return view('admin.schedulekapal.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $office = ScheduleKapal::find($id);
        // generate code
        $office->date_start = $request->date_start;
        $office->date_end = $request->date_end;
        $office->user_id = $request->user_id;
        $office->kapal_id = $request->kapal_id;
        $office->created_by = \Auth::user()->id;
        $office->save();

        return Reply::redirect(route('admin.schedulekapal.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ScheduleKapal::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
  
    public function approve($id)
    {
        $sch = ScheduleKapal::find($id);
      	$sch->status = "approved";
      	$sch->save();
        return Reply::dataOnly(['status' => 'success']);
    }
}
