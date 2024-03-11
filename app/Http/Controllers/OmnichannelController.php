<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OmnichannelController extends Controller
{
    public function index() {
      return view ('content.omnichannel.index');
    }
}
