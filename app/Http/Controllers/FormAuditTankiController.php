<?php

namespace App\Http\Controllers;
<<<<<<< HEAD

use App\Http\Controllers\Controller;
=======
use App\AuditTanki;

>>>>>>> ecf95e72501329fba4171bd49c29f34675e6abef
use Illuminate\Http\Request;

class FormAuditTankiController extends Controller
{
    public function show(){
<<<<<<< HEAD
        return view('iframe/form-audit-tanki/create');
=======
        return view('iframe/formbic/form-audit-tanki');
>>>>>>> ecf95e72501329fba4171bd49c29f34675e6abef
    }

    public function save(Request $request){
        AuditTanki::create([
            'user_id' => $request->user_id,
            'office_id' => $request->office_id,
            'no_form' => $request->no_form,
            'tanggal' => $request->tanggal,
            'posisi' => $request->posisi,
            'start_at' => $request->start_at,
            'stop_at' => $request->stop_at,
            'catatan' => $request->catatan,
            'foto' => $request->foto,
            'temuan' => $request->temuan,
            'ttd' => $request->ttd,
        ]);
    }
}
