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
    
    /*
    public  function apiUpdateConfigRunner($uid, Request $request)
    {
        $configRunner = ConfigRunner::find($uid);
        $configRunner->update($request->all());
        return $configRunner;
    }
    */
    
    public function apiCreateConfigRunner(Request $request)
    {

        $trivy = false;
        if ($request->input('trivy') == 'on') {
            $trivy = true;
        }

        $verbosity = false;
        if ($request->input('verbosity') == 'on') {
            $verbosity = true;
        }
        
        $runner = new ConfigRunner;

        $runner->uid = (string) Str::uuid();
        $runner->runner_label = $request->input('label');
        $runner->trivy = $trivy;
        $runner->kubeaudit = $request->input('kubeaudit');
        $runner->namespacesblacklist = $request->input('namespacesblacklist');
        $runner->namespaces = $request->input('namespaces');
        $runner->limit_date = $request->input('limit_date');
        $runner->limit_nr = $request->input('limit_nr');
        $runner->verbosity = $verbosity;

        $runner->save();
    }

    public function apiGetConfigRunner($uid){
        $runner = ConfigRunner::where('uid', $uid)->first();
        if (!$runner) {
            $runner['found'] = false;
        } else {
            $runner['found'] = true;
        }
        //print_r(gettype($runner));
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