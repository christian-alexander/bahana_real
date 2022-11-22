<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\OfficeWifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOfficeWifiController extends Controller
{
    public function create($id)
    {
        return view('admin.office.bssid', [
            'id' => $id
        ]);
    }
    public function store(request $request, $id)
    {
        DB::beginTransaction();
        try {
            // save into office wifi
            $officeWIfi = new OfficeWifi;
            $officeWIfi->office_id = $id;
            $officeWIfi->name = $request->name;
            $officeWIfi->bssid = $request->bssid;
            $officeWIfi->save();
            DB::commit();
            return Reply::success('Berhasil menambahkan BSSID');
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
    }
    public function edit($id)
    {
        // get wifi
        $getOfficeWifi = OfficeWifi::find($id);
        return view('admin.office.bssid', [
            'id' => $id,
            'data' => $getOfficeWifi
        ]);
    }
    public function update(request $request, $id)
    {
        DB::beginTransaction();
        try {
            // update office wifi
            $officeWIfi = OfficeWifi::find($id);
            $officeWIfi->name = $request->name;
            $officeWIfi->bssid = $request->bssid;
            $officeWIfi->save();
            DB::commit();
            return Reply::success('Berhasil mengubah BSSID');
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            // delete office wifi
            $officeWIfi = OfficeWifi::find($id);
            $officeWIfi->delete();
            DB::commit();
            return Reply::success('Berhasil menghapus BSSID');
        } catch (\Throwable $th) {
            DB::rollback();
            return Reply::error($th->getMessage());
        }
    }
}
