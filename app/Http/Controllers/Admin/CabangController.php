<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Cabang\StoreRequest;
use App\Http\Requests\Cabang\UpdateRequest;

class CabangController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.cabang');
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
        $this->groups = Cabang::with('members', 'members.user')->get();
        return view('admin.cabang.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cabang.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = Cabang::all();
        return view('admin.cabang.quick-create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $group = new Cabang();
        $group->name = $request->cabang_name;
        $group->save();

        return Reply::redirect(route('admin.cabang.index'), 'Cabang created successfully.');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function quickStore(StoreRequest $request)
    {
        $group = new Cabang();
        $group->name = $request->cabang_name;
        $group->save();

        $cabangs = Cabang::all();
        $teamData = '';

        foreach ($cabangs as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->name . ' </option>';
        }

        return Reply::successWithData('Group created successfully.', ['cabangData' => $teamData]);
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
        $this->cabang = Cabang::with('members', 'members.user')->findOrFail($id);
        return view('admin.cabang.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $group = Cabang::find($id);
        $group->name = $request->cabang_name;
        $group->save();

        return Reply::redirect(route('admin.cabang.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeDetails::where('cabang_id', $id)->update(['cabang_id' => NULL]);
        Cabang::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
