<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCwe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_cwe
            (   
                cwe_id character varying COLLATE pg_catalog."default",
                title character varying COLLATE pg_catalog."default",
                short_description character varying COLLATE pg_catalog."default",
                extended_description character varying COLLATE pg_catalog."default",
                likelihoof_of_exploit character varying COLLATE pg_catalog."default",
                common_consequences json 
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_cwe_id_fkey
                ON public.k_cwe USING btree
                (cwe_id COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

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
        Schema::dropIfExists('k_cwe');
    }
}
