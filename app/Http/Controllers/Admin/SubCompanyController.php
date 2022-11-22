<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SubCompany\StoreRequest;
use App\Http\Requests\SubCompany\UpdateRequest;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\SubCompany;
use Illuminate\Support\Facades\DB;

class SubCompanyController extends AdminBaseController
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.subcompany');
        $this->pageIcon = 'icon-user';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }
    public function index()
    {
        $this->groups = SubCompany::with('members', 'members.user')->get();
        return view('admin.subcompany.index', $this->data);
    }
    public function create()
    {
        $this->users = DB::table('users')->get();
        return view('admin.subcompany.create', $this->data);
    }
    public function quickCreate()
    {
        $this->teams = SubCompany::all();
        return view('admin.subcompany.quick-create', $this->data);
    }
    public function store(StoreRequest $request)
    {
        $group = new SubCompany();
        $group->code =$request->code;
        $group->name = $request->subcompany_name;
        $group->hrd = $request->hrd;
        $group->save();

        return Reply::redirect(route('admin.subcompany.index'), 'Data created successfully.');
    }
    public function quickStore(StoreRequest $request)
    {
        $group = new SubCompany();
        $group->name = $request->subcompany_name;
        $group->save();

        $subCompanys = SubCompany::all();
        $teamData = '';

        foreach ($subCompanys as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->name . ' </option>';
        }

        return Reply::successWithData('Group created successfully.', ['subcompanyData' => $teamData]);
    }
    public function edit($id)
    {
        $this->subcompany = SubCompany::with('members', 'members.user')->findOrFail($id);
        $this->users = DB::table('users')->get();
        return view('admin.subcompany.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $group = SubCompany::find($id);
        $group->code =$request->code;
        $group->name = $request->subcompany_name;
        $group->hrd = $request->hrd;
        $group->save();

        return Reply::redirect(route('admin.subcompany.index'), __('messages.updatedSuccessfully'));
    }
    public function destroy($id)
    {
        EmployeeDetails::where('sub_company_id', $id)->update(['sub_company_id' => NULL]);
        SubCompany::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
