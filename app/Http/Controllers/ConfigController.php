<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ConfigRunner;
use Illuminate\Support\Str;

class ConfigController extends Controller
{

    public function main() 
    {

        $runner = new ConfigRunner;

        $runner->uid = (string) Str::uuid();
        $runner->runner_label = "asdf";

//        $runner->save();

        $configRunner = ConfigRunner::all();
        $data['configRunner'] = $configRunner;
      
        
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        
        return view('config.main', $data);
    }
}