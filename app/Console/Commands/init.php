<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'klustair:init {action} {token?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Klustair related init tasks';

    protected $help = 'Available actions: 
    - waitForDB
    - createAdmin
    - initAPItoken [<token>]';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        switch ($this->argument('action')) {
            case 'waitForDB':
                $this->waitForDB();
                break;
            case 'createAdmin':
                $this->createAdmin();
                break;
            case 'initAPItoken':
                $this->initAPItoken();
                break;
            default:
                $this->error('Action not found');
                break;
        }
        return 0;
    }

    private function waitForDB()
    {
        $limit = 20;
        $retry = 0;
        $sleep = 3;
        $check = false;
        $this->line(" Waiting for DB Connection\n\n");

        while (!$check && $limit > $retry) {
            try {
                $check = DB::connection()->getPdo();
            } catch (\PDOException $e) {
                $this->error(' No DB available yet. Will wait a bit and retry ... ');
            }
            $retry++;
            sleep($sleep);
        }

        if ($check){
            $this->info(' DB Connection established');
        } else {
            $this->error("\n No BD Connection! Check your Database and Credentials.");
        }
    }

    private function initAPItoken() {

        $name = "initial runner Token";
        $email = config('klustair.adminEmail', "admin@admin.com");

        if ($this->argument('token')) {
            $tokenstr = $this->argument('token');
            $plainTextToken = $tokenstr;
        } else {
            $plainTextToken = Str::random(40);
            $tokenstr = hash('sha256', $plainTextToken);
        }

        # skip if user not exists
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error(" You need to create the admin account first ");
            return;
        }

        try {
            $user = User::where('email', $email)->first();
            DB::table('personal_access_tokens')->insertOrIgnore([
                'tokenable_type' => 'App\Models\User',
                'tokenable_id' => $user->id,
                'token' => $tokenstr,
                'name' => $name,
                'abilities' => '["*"]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $this->info(" Token '${name}' -> '${plainTextToken}' has been inserted sucessfull! ");
        } catch (\Throwable $e) {
            $this->error(' Something went wrong! Token has not been generated! ');
            $this->line($e->getMessage().PHP_EOL);
        }
        
    }

    private function createAdmin() {
        $name = config('klustair.adminUser', "admin");
        $email = config('klustair.adminEmail', "admin@admin.com");
        $password = config('klustair.adminPassword', Str::random(12));
        if ($password == "") {
            $password = Str::random(12);
        }
        $hashed_password = Hash::make($password);

        # If user already exists, skip
        $user = User::where('email', $email)->first();
        if ($user) {
            $this->info("Admin with email '$email' allready exists");
            return;
        }

        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = $hashed_password;

        try {
            $user->save();
            $this->info("Admin '${email}' has been created sucessfull!");
            $this->comment("Password: ${password}");

        } catch (QueryException $e) {
            $this->error('Something went wrong! Admin has not been saved!');
            $this->line($e->getMessage().PHP_EOL);
        }
    }
}
