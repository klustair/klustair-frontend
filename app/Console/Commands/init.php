<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
}
