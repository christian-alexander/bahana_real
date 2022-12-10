<?php

namespace App\Http\Controllers;

use App\User;
use App\Helper\Files;
use App\FormPerijinan;
use App\JenisPerijinan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormPerijinanController extends Controller
{
    //
    public function create($user_id)
    {   
        $data['user'] = User::find($user_id);

        $data['jenis_perijinans'] = JenisPerijinan::all();

        return view('iframe/form-perijinan/create',$data);
    }
    public function doInput(Request $request)
    {
        $validated_data = $request->validate([
            'user_id' => 'required',
            'nama_perusahaan' => 'required',
            'pihak_kedua' => 'required',
            'jenis_perijinan_id' => 'required|integer',
            'no_perijinan' => 'required', 
            'start_berlaku' => 'required|date', 
            'end_berlaku' => 'required|date|after_or_equal:start_berlaku', 
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

            // notifikasi
            $userLogin = User::find($validated_data['user_id']);
            $msg = "$userLogin->name membuat form perijinan";
            try {
                $user->notify(new FormCreated($msg,'FORM-ASURANSI-PERIJINAN', FormAsuransiJiwa::class));
            } catch (\Throwable $th) {
                $flagErrorMail = true;
            }
            
            return redirect()->back()->with('success','Berhasil Input Data');
        }else{
            return redirect()->back()->with('danger','Gagal Input Data');
        }
    }

    private function convert_attachment_to_json($request){
        $arr_attachment = [];
        if (isset($request->attachment) && !empty($request->attachment)) {
            foreach($request->attachment as $attachment){
                $filename = Files::uploadLocalOrS3($attachment, "form-perijinan");
                array_push( $arr_attachment,"user-uploads/form-perijinan/$filename" );
            }
        }

        return json_encode($arr_attachment);
    }
}
