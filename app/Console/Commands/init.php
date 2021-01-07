<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $host = config('database.connections.pgsql.host');
        $port = config('database.connections.pgsql.port');
        $limit = 20;
        $retry = 0;
        $sleep = 3;

        while (!is_resource(@fsockopen($host, $port)) && $limit > $retry) {
            $this->line(' Waiting for DB Connection');
            $retry++;
            sleep($sleep);
        }
        if (is_resource(@fsockopen($host, $port))){
            $this->info('DB Connection OK');
        } else {
            $this->error('DB Connection Failed');
        }
    }
}
