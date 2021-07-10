<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNamespaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_namespaces
            (
                name character varying COLLATE pg_catalog."default" NOT NULL,
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                report_uid character varying COLLATE pg_catalog."default" NOT NULL,
                creation_timestamp timestamp with time zone NOT NULL,
                kubernetes_namespace_uid character varying COLLATE pg_catalog."default",
                CONSTRAINT k_namespaces_pkey PRIMARY KEY (report_uid, uid),
                CONSTRAINT k_namespaces_uid_key UNIQUE (uid),
                CONSTRAINT k_namespaces_report_uid_fkey FOREIGN KEY (report_uid)
                    REFERENCES public.k_reports (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE CASCADE
                    NOT VALID
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;
        
        $dbuser = env('DB_USERNAME', 'postgres');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_namespaces
                OWNER to $dbuser;
        SQL;

        if($dbuser = getenv('DB_USERNAME')){
            $create_sql[] = <<<SQL
            ALTER TYPE public.k_namespaces
                OWNER to $dbuser;
            SQL;
        }

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_namespaces_report_uid_fkey
                ON public.k_namespaces USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
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
        Schema::dropIfExists('k_namespaces');
    }
}
