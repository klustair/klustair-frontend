<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $create_sql[] = <<<SQL
            CREATE SEQUENCE IF NOT EXISTS public.reports_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 2147483647
                CACHE 1;
        SQL;

        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_reports
            (   
                id integer NOT NULL DEFAULT nextval('reports_id_seq'::regclass),
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                checktime timestamp with time zone NOT NULL,
                title character varying COLLATE pg_catalog."default",
                CONSTRAINT k_reports_test_pkey PRIMARY KEY (uid),
                CONSTRAINT k_reports_test_uid_key UNIQUE (uid)
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_reports_checktime
                ON public.k_reports USING btree
                (checktime ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $dbuser = env('DB_USERNAME', 'postgres');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_reports
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            ALTER SEQUENCE public.migrations_id_seq
                OWNER to $dbuser;
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
        Schema::dropIfExists('k_reports');
    }
}
