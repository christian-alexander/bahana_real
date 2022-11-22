<?php

namespace Modules\RestAPI\Http\Controllers;

use App\ProjectMember;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Support\Facades\DB;
use Modules\RestAPI\Entities\Project;
use Modules\RestAPI\Http\Requests\Projects\IndexRequest;
use Modules\RestAPI\Http\Requests\Projects\CreateRequest;
use Modules\RestAPI\Http\Requests\Projects\UpdateRequest;
use Modules\RestAPI\Http\Requests\Projects\ShowRequest;
use Modules\RestAPI\Http\Requests\Projects\DeleteRequest;

class ProjectController extends ApiBaseController
{
    protected $model = Project::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = CreateRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $showRequest = ShowRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function modifyIndex($query)
    {
        // $query = $query->leftjoin('project_members','project_members.project_id','projects.id')

      	return $query;
       // return $query->visibility();
    }

    public function stored(Project $project)
    {
        $user = \Auth::user();
        $member = new ProjectMember;
        $member->user_id = $user->id;
        $member->project_id = $project->id;
        $member->save();

        // update project
        $modelUpdate = Project::find($project->id);
        if (!empty($modelUpdate)) {
            $modelUpdate->wilayah_id = $user->employeeDetail->wilayah_id;
            $modelUpdate->subcompany_id = $user->employeeDetail->sub_company_id;
            $modelUpdate->save();
        }
    }

    public function members($projectId)
    {
        $project =  Project::find($projectId);
        if (request()->get('members')) {
            $ids = array_column(request()->get('members'), 'id');
            $project->members_many()->sync($ids);
        }

        return ApiResponse::make('Project member added successfully');
    }
    public function memberRemove($projectId, $id)
    {
        $project =  Project::find($projectId);
        $project->members_many()->detach($id);

        return ApiResponse::make('Member removed');
    }
    public function getList()
    {
        $team_id = request()->get('team_id');
        $wilayah_id = request()->get('wilayah_id');
        $subcompany_id = request()->get('subcompany_id');
        $category_id = request()->get('category_id');
        $project_name = request()->get('project_name');
        $limit = empty(request()->get('limit'))?100:request()->get('limit');
        $user = \Auth::user();

        // get member 
        $member = DB::table('projects')
            ->select('projects.*')
            ->join('project_members','project_members.project_id','projects.id')
            ->where('user_id', $user->id)
            ->where('deleted_at', null)
            ->groupBy('project_id')
            ->get()->toArray();
        $data = DB::table('projects')->select('*')
            ->where('deleted_at', null);
            // ->orWhereIn('id',$member);
        if (isset($team_id) && !empty($team_id)){ 
            $data = $data->where('team_id',$team_id);
        }
        if (isset($wilayah_id) && !empty($wilayah_id)){ 
            $data = $data->where('wilayah_id',$wilayah_id);
        }
        if (isset($subcompany_id) && !empty($subcompany_id)){ 
            $data = $data->where('subcompany_id',$subcompany_id);
        }
        if (isset($category_id) && !empty($category_id)){ 
            $data = $data->where('category_id',$category_id);
        }
        if (isset($project_name) && !empty($project_name)){ 
            $data = $data->where('project_name','like','%'.$project_name.'%');
        }
        $data = $data->limit($limit)
            ->orderBy('project_name','asc')
            ->get()->toArray();
        $output = array_merge($data, $member);
        $output = $this->my_array_unique($output);
        $output = collect($output)->sortBy('project_name')->toArray();
        $output = array_values($output);
        return ApiResponse::make('Get data success',$output);
    }

    function my_array_unique($array, $keep_key_assoc = false){
        $duplicate_keys = array();
        $tmp = array();       
    
        foreach ($array as $key => $val){
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val))
                $val = (array)$val;
    
            if (!in_array($val, $tmp))
                $tmp[] = $val;
            else
                $duplicate_keys[] = $key;
        }
    
        foreach ($duplicate_keys as $key)
            unset($array[$key]);
    
        return $keep_key_assoc ? $array : array_values($array);
    }
}
