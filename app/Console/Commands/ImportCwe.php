<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCwe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'klustair:importcwe {version}';

    public $url;
    public $file_name;
    public $database_name;
    public $cwe_version;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import latest CWE informations';

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
        $this->cwe_version = $this->argument('version');

        $this->url = 'https://cwe.mitre.org/data/xml/cwec_v'.$this->cwe_version.'.xml.zip';
        $this->file_name = storage_path('app/cwec_v'.$this->cwe_version.'.xml.gz');
        $this->xml_name = storage_path('app/cwec_v'.$this->cwe_version.'.xml');
        $this->extract_dir = storage_path('app');
        
        //$this->download();
        //$this->unzip();
        $this->createDB();
        $this->importData();
        return 0;
    }


    public function download()
    {
        $this->comment('Download file : '.$this->url);
        set_time_limit(0);
        
        $fp = fopen($this->file_name, 'w+');
        
        $curl = curl_init(str_replace(" ", "%20", $this->url));
        curl_setopt($curl, CURLOPT_TIMEOUT, 50);
        
        curl_setopt($curl, CURLOPT_FILE, $fp);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        
        curl_exec($curl);
        curl_close($curl);
        fclose($fp);
    }

    public function unzip()
    {
        $this->comment('Unzip file : '.$this->file_name);
        $zip_obj = new \ZipArchive;
        $zip_obj->open($this->file_name);
        $zip_obj->extractTo($this->extract_dir);
        $this->comment('Extracted file : '.$this->xml_name);
    }

    public function createDB()
    {
        $this->comment('Create Table: k_cwe');
        Schema::dropIfExists('k_cwe');

        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_cwe
            (   
                cwe_id character varying COLLATE pg_catalog."default",
                title character varying COLLATE pg_catalog."default",
                short_description character varying COLLATE pg_catalog."default",
                extended_description character varying COLLATE pg_catalog."default",
                likelihoof_of_exploit character varying COLLATE pg_catalog."default",
                common_consequences character varying COLLATE pg_catalog."default"
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $dbuser = env('DB_USERNAME', 'postgres');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_cwe
                OWNER to $dbuser;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }

    public function importData()
    {   
        $this->comment('Importing CWE Data');
        $xml = simplexml_load_file($this->xml_name);

        foreach($xml->Weaknesses as $weakness){
            foreach($weakness as $w){
                //echo $w->Description . PHP_EOL;
                //echo $w->attributes()->ID . PHP_EOL;
                DB::table('k_cwe')->insert([
                    'cwe_id' => 'CWE-'.$w->attributes()->ID,
                    'title' => $w->attributes()->Name,
                    'short_description' => $w->Description,
                    'extended_description' => $w->Extended_Description,
                    'likelihoof_of_exploit' => $w->Likelihood_Of_Exploit,
                    'common_consequences' => '0'
                ]);
            }
        }
    }
}
