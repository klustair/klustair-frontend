<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


use App\Models\ConfigRunner;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class ConfigController extends Controller
{
    public function apiCreateConfigRunner(Request $request)
    {
        
        $runner = new ConfigRunner;

        $runner->uid = (string) Str::uuid();
        $runner->runner_label = "asdf";

        $runner->save();
    }

    public function main() 
    {

        $configRunner = ConfigRunner::all();
        $data['configRunner'] = $configRunner;
        
        $users = User::all();
        $data['users'] = $users;

        $tokens = PersonalAccessToken::all();
        $data['tokens'] = $tokens;

      
        return view('config.main', $data);
    }
}