<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoController extends Controller
{
    public function create () {
      return view('iframe.po.create');
    }

    public function show () {
      return view('iframe.po.edit');
    }
}
