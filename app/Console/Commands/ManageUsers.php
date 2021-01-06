<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class ManageUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'klustair:user {action} {email?} {fullname?}';

    protected $name;
    protected $email;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Klustair Users';

    protected $help = 'Available actions: 
    - create [<email> [<fullname>]]
    - list
    - delete [<email>]
    ';

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
            case 'create':
                $this->create();
                break;
            
            case 'delete':
                $this->delete();
                break;
            
            case 'list':
                $this->list();
                break;
            
            default:
                # code...
                break;
        }
        return 0;
    }

    private function create()
    {
        if ($this->argument('fullname')) {
            $name = $this->argument('fullname');
        } else {
            $name = $this->ask('Full name');
        }
        if ($this->argument('email')) {
            $email = $this->argument('email');
        } else {
            $email = $this->ask('Email');
        }
        
        $password = $this->secret('Define a Password or leave blank for autogenerate');

        if ($password == '') { $password = $this->random_str(16); }
        $hashed_password = Hash::make($password);

        $user = new User;

        $user->name = $name;
        $user->email = $email;
        $user->password = $hashed_password;

        try {
            $user->save();
            $this->info("User '${email}' has been created sucessfull!");
            $this->comment("Password: ${password}");

        } catch (QueryException $e) {
            $this->error('Something went wrong! User has not been saved!');
            $this->line($e->getMessage().PHP_EOL);
        }
    }

    private function delete()
    {   
        if ($this->argument('email')) {
            $email = $this->argument('email');
        } else {
            $email = $this->ask('Email');
        }
        $deletedRows = User::where('email', $email )->delete();
        if ($deletedRows > 0) {
            $this->info("User '${email}' has been delete sucessfull!");
        } else {
            $this->error("User '${email}' not found!");
        }
    }

    private function list()
    {
        $this->table(
            ['Name', 'Email'],
            User::all(['name', 'email'])->toArray()
        );
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
