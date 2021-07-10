<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigRunner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_config_runner
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                runner_label character varying COLLATE pg_catalog."default",
                kubeaudit character varying COLLATE pg_catalog."default",
                verbosity boolean,
                namespacesblacklist character varying COLLATE pg_catalog."default",
                namespaces character varying COLLATE pg_catalog."default",
                anchore boolean,
                trivy boolean,
                trivycredentialspath character varying COLLATE pg_catalog."default",
                limit_date character varying COLLATE pg_catalog."default",
                limit_nr integer,
                CONSTRAINT k_runner_configs_pkey PRIMARY KEY (uid)
            )
        SQL;

        if($dbuser = getenv('DB_USERNAME')){
            $create_sql[] = <<<SQL
            ALTER TYPE public.k_config_runner
                OWNER to $dbuser;
            SQL;
        }

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('k_config_runner');
    }
}
