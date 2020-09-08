<?php

namespace App\Http\Controllers\Anchore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SystemController extends Controller
{
    public function list() 
    {   
        $data=[];
        return view('anchore/system', $data);
    }
}
