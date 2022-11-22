<?php

namespace App\Http\Controllers\Admin;

use App\Cabang;
use App\EmployeeDetails;
use App\Form;
use App\FormField;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\FormBuilder\StoreRequest;
use App\Http\Requests\FormBuilder\UpdateRequest;
use Illuminate\Support\Facades\Schema;

class AdminFormBuilderController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Form Builder';
        // $this->pageTitle = __('app.menu.office');
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
        $this->form = Form::with(['field'])->get();

        return view('admin.form-builder.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.form-builder.create', $this->data);
    }


    public function preDefineTable($data){
        $data = str_replace(' ','',$data);
        // add prefix form_
        return 'form_'.strtolower($data);
    }
    public function store(StoreRequest $request)
    {
        // return $request->atribut;
        // insert into form
        $form = new Form;
        $form->table_name = $this->preDefineTable($request->nama_form);
        $form->save();

        
        $json_atribut = json_decode($request->atribut);
        // add primary key
        $this->addPrimary($form);
        $arr_table =[
            [
                "type" =>'increments',
                "field" =>'id',
                "option" =>null,
                "nullable"=> 0,
                "value"=> null,
                "pk"=>1
            ]
        ];
        $arr_option=[];
        foreach ($json_atribut as $atribut) {
            // insert into form_field
            $form_field = new FormField;
            $form_field->form_id = $form->id;
            $form_field->label = $atribut->label;
            $form_field->field_type = $this->mappingType($atribut->type);
            $form_field->field_name = $atribut->name;
            $form_field->field_default_value = !isset($atribut->value)?null:$atribut->value;
            $form_field->nullable = $atribut->required?0:1;
            $form_field->reference_table_name = $atribut->description;
            $form_field->reference_field_name = $atribut->placeholder;
            if ($atribut->type=='select') {
                $arr_select = explode(',',$atribut->className);
                $form_field->dropdown_table_name = $arr_select[0];
                $form_field->dropdown_table_value = $arr_select[1];
                $form_field->dropdown_table_label = $arr_select[2];
                $form_field->dropdown_option = json_encode($atribut->values);
                foreach ($atribut->values as $val) {
                    array_push($arr_option,$val->value);
                }
            }
            $form_field->save();
            array_push($arr_table,[
                "type"=> $form_field->field_type,
                "field"=> $form_field->field_name,
                "option" =>$arr_option,
                "nullable"=> $form_field->nullable,
                "value"=>$form->field_default_value,
                "pk"=>0
            ]);
        }
        // return $arr_table;
        Schema::create($form->table_name, function($table) use ($arr_table)
        { 
            foreach ($arr_table as $item) {
                $type = $item['pk']==1?'increments':$item['type'];
                if ($type=='enum') {
                    $set_table = $table->{$type}($item['field'],$item['option']);
                }else{
                    $set_table = $table->{$type}($item['field']);
                }
                if ($item['nullable']==1) {
                    $set_table->nullable();
                }
                if (!empty($item['value'])) {
                    $set_table->default($item['value']);
                }
            }
        });
        return Reply::redirect(route('admin.form-builder.index'), 'Pertanyaan created successfully.');
    }
    public function mappingType($type){
        $resp = 'string';
        if ($type=='text') {
            $resp = 'string';
        }elseif($type=='textarea'){
            $resp = 'text';
        }elseif($type=='select'){
            $resp = 'enum';
        }
        return $resp;
    }
    public function addPrimary($form){
        $form_field = new FormField;
        $form_field->form_id = $form->id;
        $form_field->label = 'id';
        $form_field->field_type = 'bigint';
        $form_field->field_name = 'id';
        $form_field->field_default_value = null;
        $form_field->nullable = 0;
        $form_field->pk = 1;
        $form_field->reference_table_name = null;
        $form_field->reference_field_name = null;
        $form_field->save();
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
        $this->form_field = FormField::where('form_id', $id)->get();
        return view('admin.form-builder.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->pertanyaan = Pertanyaan::findOrFail($id);
        return view('admin.pertanyaan.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $office = Pertanyaan::find($id);
        $office->pertanyaan = $request->pertanyaan;
        $office->save();

        return Reply::redirect(route('admin.pertanyaan.index'), __('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $form = Form::find($id);
        // delete table
        $check_table = \Schema::hasTable($form->table_name);
        if ($check_table) {
            Schema::drop($form->table_name);
        }
        $form->delete();
        return Reply::dataOnly(['status' => 'success']);
    }
}
