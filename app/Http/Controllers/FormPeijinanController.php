<?php

namespace App\Http\Controllers;

use App\JenisPerijinan;
use App\FormPerijinan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormPerijinanController extends Controller
{
    //
    public function create()
    {
        $data['jenis_perijinans'] = JenisPerijinan::all();

        return view('iframe/form-perijinan/create',$data);
    }
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
            'nama_perusahaan' => 'required',
            'pihak_kedua' => 'required',
            'jenis_perijinan_id' => 'required|integer',
            'no_perijinan' => 'required', 
            'start_berlaku' => 'required', 
            'end_berlaku' => 'required', 
            'posisi_dokumen' => 'required', 
            'nama_pic' => 'required', 
            'no_hp' => 'required', 
            'email' => 'required', 
            'jabatan' => 'required', 
            'note' => 'required'
        ]);

        $validated_data['status'] = 1;

        // file attachment
        $validated_data['attachment'] = $this->convert_attachment_to_json($request);

        if(FormPerijinan::create($validated_data)){
            //register berhasil
            return redirect()->back()->with('success','Berhasil Input Data');
        }else{
            return redirect()->back()->with('danger','Gagal Input Data');
        }
    }

    private function convert_attachment_to_json($request){
        $arr_attachment = [];
        if (isset($request->attachment) && !empty($request->attachment)) {
            foreach($request->attachment as $attachment){
                $filename = Files::uploadLocalOrS3($attachment, "form-perijinan/$request->user_id");
                array_push( $arr_attachment,"user-uploads/form-perijinan/$request->user_id/$filename" );
            }
        }

        return json_encode($arr_attachment);
    }
}
