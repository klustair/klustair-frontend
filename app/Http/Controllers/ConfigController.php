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

    
    public function apiDeleteUser($uid)
    {
        User::destroy($uid);
    }

    public function apiDeleteToken($uid)
    {
        PersonalAccessToken::destroy($uid);
    }

    public function apiCreateToken(Request $request)
    {
        $token = $request->user()->createToken($request->tokenName);
        return ['token' => $token->plainTextToken];
    }

    public function apiDeleteConfigRunner($uid)
    {
        ConfigRunner::destroy($uid);
    }

    public function apiCreateConfigRunner(Request $request)
    {
        
        $runner = new ConfigRunner;

        $runner->uid = (string) Str::uuid();
        $runner->runner_label = $request->input('label');
        $runner->kubeaudit = $request->input('kubeaudit');
        $runner->namespacesblacklist = $request->input('namespacesblacklist');
        $runner->namespaces = $request->input('namespaces');
        $runner->limit_date = $request->input('limit_date');
        $runner->limit_nr = $request->input('limit_nr');
        $runner->verbosity = $request->input('verbosity');

        $runner->save();
    }

    public function apiGetConfigRunner($uid){
        $runner = new ConfigRunner;
        $runner = ConfigRunner::where('uid', $uid)->first();
        return $runner;

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