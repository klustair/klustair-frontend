<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'klustair:init {action} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Klustair related init tasks';

    protected $help = 'Available actions: 
    - waitForDB';
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
        $limit = 3;
        $retry = 0;
        $sleep = 3;
        $check = false;
        $this->line(" Waiting for DB Connection\n\n");

        while (!$check && $limit > $retry) {
            try {
                $check = DB::connection()->getPdo();
            } catch (\PDOException $e) {
                $this->error(' DB Connection Failed ');
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
        $email = "admin@admin.com";

        try {
            $user = User::where('email', $email)->first();
            $token = $user->createToken($name);
            $this->info("Token '${name}' -> '$token->plainTextToken' has been created sucessfull!");

        } catch (\Throwable $e) {
            $this->error('Something went wrong! Token has not been generated!');
        }
    }

    private function createAdmin() {
        $name = "admin";
        $email = "admin@admin.com";
        $password = $this->random_str(16);
        $hashed_password = Hash::make($password);
        try {
            User::updateOrCreate(
                ['name' => $name], 
                ['password' => $hashed_password], 
                ['email' => $email], 
            );
            $this->info("Admin '${name}' with '$email' has been created sucessfull!");
            $this->comment("Password: ${password}");

        } catch (QueryException $e) {
            $this->error('Something went wrong! User has not been saved!');
            $this->line($e->getMessage().PHP_EOL);
        }
    }

    /**
     * Props to Scott Arciszewski
     * https://stackoverflow.com/questions/4356289/php-random-string-generator 
     * 
     * Generate a random string, using a cryptographically secure 
     * pseudorandom number generator (random_int)
     * 
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    private function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
