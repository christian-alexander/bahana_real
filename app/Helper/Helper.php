<?php

use App\EmployeeDetails;
use App\Office;
use Carbon\Carbon;

function setPermissionEmployee($str)
{
    $str = str_replace(" ", "_", $str);
    $str = strtolower($str);
    return $str;
}
function setCheckedPermissionEmployee($data, $selected)
{
    $resp = '';
    $str = json_decode($data);
    if (!empty($str)) {
        if (in_array($selected, $str)) {
            $resp = 'checked';
        }
    }
    return $resp;
}
function getPermissionEmployee($data)
{
    $resp = '';
    $item = json_decode($data);
    $arr_subcompany = [];
    $arr_wilayah = [];
    $arr_cabang = [];

    foreach ($item as $val) {
        if (preg_match("/subcompany./i", $val) == true) {
            $permission = str_replace('subcompany.', '', $val);
            $permission = str_replace('_', ' ', $permission);
            array_push($arr_subcompany, ucwords($permission));
        } elseif (preg_match("/wilayah./i", $val) == true) {
            $permission = str_replace('wilayah.', '', $val);
            $permission = str_replace('_', ' ', $permission);
            array_push($arr_wilayah, ucwords($permission));
        } elseif (preg_match("/cabang./i", $val) == true) {
            $permission = str_replace('cabang.', '', $val);
            $permission = str_replace('_', ' ', $permission);
            array_push($arr_cabang, ucwords($permission));
        }
    }
    return [
        "subcompany" => $arr_subcompany,
        "wilayah" => $arr_wilayah,
        "cabang" => $arr_cabang,
    ];
}
function generate_office_code()
{
    $code = Carbon::now()->format('YmdHis');
    $check = 0;
    while ($check == 0) {
        $office = Office::where('code', $code)->count();
        if ($office > 0) {
            $code = $code . $office;
        } else {
            $check = 1;
        }
    }
    return $code;
}
function currency_rupiah($val)
{
    return 'Rp. ' . number_format($val, 0, "", ".");
}
function public_file($url)
{
    return "public/user-uploads/$url";
}
function getDayInIndonesia(String $day){
    $day = strtolower($day);
    $response = '';
    if ($day =='monday') {
        $response ='senin';
    }elseif($day =='tuesday'){
        $response ='selasa';
    }elseif($day =='wednesday'){
        $response ='rabu';
    }elseif($day =='thursday'){
        $response ='kamis';
    }elseif($day =='friday'){
        $response ='jumat';
    }elseif($day =='saturday'){
        $response ='sabtu';
    }else{
        //Sunday 
        $response ='minggu';
    }
    return $response;
}

function model_response(bool $success,$msg, $data = null){
    return[
        "success"=>$success,
        "msg"=>$msg,
        "data"=>$data,
    ];
}
function masking_status_approval($approval){
    $output ="-";
    if (isset($approval) && !empty($approval)) {
        if ($approval->status=="approved_1") {
            $output = "Disetujui Nahkoda";
        }elseif($approval->status=="approved_2"){
            $output = "Disetujui Admin";
        }elseif($approval->status=="approved_3"){
            $output = "Disetujui Manager";
        }elseif($approval->status=="rejected_1"){
            $output = "Ditolak Nahkoda ($approval->rejected_reason)";
        }elseif($approval->status=="rejected_2"){
            $output = "Ditolak Admin ($approval->rejected_reason)";
        }elseif($approval->status=="rejected_3"){
            $output = "Ditolak Manager ($approval->rejected_reason)";
        }
    }
    return $output;
}
function formatedDate($date){
    $output ="-";
    if (!empty($date)) {
        $arr= explode('||',$date);
        if (isset($arr[1]) && !empty($arr[1])){ 
            $output = Carbon::parse($arr[0])->format('d-m-Y').' (SYTEM)';
        }else{
            $output = Carbon::parse($date)->format('d-m-Y');
        }
    }
    return $output;
}

function formatedDateTime($date){
    $output ="-";
    if (!empty($date)) {
        $output = Carbon::parse($date)->format('l, d-m-Y H:i');
    }
    return $output;
}
function formatedDateTimeWithoutHI($date){
    $output ="-";
    if (!empty($date)) {
        $output = Carbon::parse($date)->format('l, d-m-Y');
    }
    return $output;
}
function formatedDateTimeWithoutHIForWFO($date){
    $output ="-";
    $arr = explode('|',$date);
    $id ='';
    if (isset($arr[1]) && !empty($arr[1])){ 
        $id = " <b>( ID: ".$arr[1]." )</b>";
    }
    if (!empty($arr[0])) {
        $output = Carbon::parse($arr[0])->format('l, d-m-Y');
    }
    return $output.$id;
}
function array_insert(&$array, $position, $insert){
    if (is_int($position)) {
        array_splice($array, $position, 0, $insert);
    } else {
        $pos   = array_search($position, array_keys($array));
        $array = array_merge(
            array_slice($array, 0, $pos),
            $insert,
            array_slice($array, $pos)
        );
    }
}
