<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Passport\HasApiTokens;

class ManageTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'klustair:token {action?} {name?} {email?}';

    protected $name;
    protected $email;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage Klustair API Tokens';

    protected $help = 'Available actions: 
    - create [<name> [<email>]]
    - list
    - delete [<name>]';
    
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
                $this->error('Wrong or no Action defined. Options: create|delete|list');
                break;
        }
        return 0;
    }

    private function create()
    {
        if ($this->argument('name')) {
            $name = $this->argument('name');
        } else {
            $name = $this->ask('Token name or purpose');
        }
        if ($this->argument('email')) {
            $email = $this->argument('email');
        } else {
            $email = $this->ask('Owners Email');
        }

        try {
            $user = User::where('email', $email)->first();
            $token = $user->createToken($name);
            $this->info("Token '${name}' -> '$token->plainTextToken' has been created sucessfull!");

        } catch (\Throwable $e) {
            $this->error('Something went wrong! Token has not been generated!');
            //$this->line($e->getMessage().PHP_EOL);
        }
    }

    private function delete()
    {   
        if ($this->argument('name')) {
            $name = $this->argument('name');
        } else {
            $name = $this->ask('Token name');
        }

        $deletedRows = PersonalAccessToken::where('name', $name )->delete();
        if ($deletedRows > 0) {
            $this->info("Token '${name}' has been delete sucessfull!");
        } else {
            $this->error("User '${name}' not found!");
        }
    }

    private function list()
    {
        $this->table(
            ['Name', 'Token', 'last used', 'created'],
            PersonalAccessToken::all(['name', 'token', 'last_used_at', 'created_at' ])->makeVisible(['token'])->toArray()
        );
    }
}
