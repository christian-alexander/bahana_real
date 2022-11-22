<?php

namespace App\Http\Controllers\Admin;

use App\Wilayah;
use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Wilayah\StoreRequest;
use App\Http\Requests\Wilayah\UpdateRequest;

class WilayahController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.menu.wilayah');
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
        $this->groups = Wilayah::with('members', 'members.user')->get();
        return view('admin.wilayah.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.wilayah.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = Wilayah::all();
        return view('admin.wilayah.quick-create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $group = new Wilayah();
        $group->code =$request->code;
        $group->name = $request->wilayah_name;
        $group->save();

        return Reply::redirect(route('admin.wilayah.index'), 'Wilayah created successfully.');
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function quickStore(StoreRequest $request)
    {
        $group = new Wilayah();
        $group->name = $request->wilayah_name;
        $group->save();

        $wilayahs = Wilayah::all();
        $teamData = '';

        foreach ($wilayahs as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->name . ' </option>';
        }

        return Reply::successWithData('Group created successfully.', ['wilayahData' => $teamData]);
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
        $this->wilayah = Wilayah::with('members', 'members.user')->findOrFail($id);
        return view('admin.wilayah.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $group = Wilayah::find($id);
        $group->code =$request->code;
        $group->name = $request->wilayah_name;
        $group->save();

        return Reply::redirect(route('admin.wilayah.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeDetails::where('wilayah_id', $id)->update(['wilayah_id' => NULL]);
        Wilayah::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}
