<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigController extends Controller
{

    public function main() 
    {
        $data = array();
        return view('config.main', $data);
    }
}