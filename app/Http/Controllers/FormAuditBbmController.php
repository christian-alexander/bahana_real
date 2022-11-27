<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Office;
use App\User;
use App\FormAuditBbm;
use Carbon\Carbon;
use App\Helper\Files;

class FormAuditBbmController extends Controller
{
    public function show($user_id){
        $data['user'] = User::find($user_id);
        $data['offices'] = Office::where('is_kapal',1)->get();

        if($data['user'] == NULL){ dd('user tidak ditemukan'); }

        return view('iframe/form-audit-bbm/create',$data);
    }


    public function save(Request $request){

        if (isset($request->upload) && !empty($request->upload)) {
            $filename = Files::uploadLocalOrS3($request->upload, "form-audit-bbm/$request->user_id");
            $upload = "user-uploads/form-audit-bbm/$request->user_id/$filename";
        }
        if (isset($request->lampiran) && !empty($request->lampiran)) {
            $filename2 = Files::uploadLocalOrS3($request->lampiran, "form-sounding-cargo/$request->user_id");
            $lampiran = "user-uploads/form-audit-bbm/$request->user_id/$filename2";
        }
        if (isset($request->upload2) && !empty($request->upload2)) {
            $filename3 = Files::uploadLocalOrS3($request->upload2, "form-audit-bbm/$request->user_id");
            $upload2 = "user-uploads/form-audit-bbm/$request->user_id/$filename3";
        }

        $current_timestamp = Carbon::now();
        FormAuditBbm::create([
            'no_form' => $request->no_form,
            'tanggal' => $request->tanggal,
            'users_id' => $request->user_id,
            'kapal_id' => $request->kapal,
            'posisi' => $request->posisi,
            'start_at' => $request->start_at,
            'stop_at' => $current_timestamp,
            'kompartemen' => $request->kompartemen,
            'produk' => $request->produk,
            'tinggi' => $request->ketinggian,
            'volume' => $request->volume,
            'foto_komp' => $upload2,
            'sounding_oob' => $request->sounding,
            'lampiran' => $lampiran,
            'catatan' => $request->catatan,
            'temuan' => $request->temuan,
            'ttd_perwira' => $request->ttd,
            'foto_perwira' => $upload,
        ]);
    }
}
