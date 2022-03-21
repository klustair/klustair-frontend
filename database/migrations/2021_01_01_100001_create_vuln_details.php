<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVulnDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $create_sql[] = <<<SQL
            CREATE SEQUENCE IF NOT EXISTS public.vuln_details_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 2147483647
                CACHE 1;
        SQL;

        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_vuln_details
                (
                    id integer NOT NULL DEFAULT nextval('vuln_details_id_seq'::regclass),
                    vulnerability_id character varying COLLATE pg_catalog."default" NOT NULL,
                    pkg_name character varying COLLATE pg_catalog."default" NOT NULL,
                    descr character varying COLLATE pg_catalog."default",
                    title character varying COLLATE pg_catalog."default",
                    installed_version character varying COLLATE pg_catalog."default",
                    fixed_version character varying COLLATE pg_catalog."default",
                    severity_source character varying COLLATE pg_catalog."default",
                    severity double precision,
                    last_modified_date character varying COLLATE pg_catalog."default",
                    published_date character varying COLLATE pg_catalog."default",
                    links jsonb,
                    cvss jsonb,
                    cwe_ids jsonb,
                    CONSTRAINT k_vuln_details_pkey PRIMARY KEY (vulnerability_id, pkg_name, installed_version)
                )
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_details_severity
                ON public.k_vuln_details USING btree
                (severity ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_details_severity_fixed_vulnerability_id
                ON public.k_vuln_details USING btree
                (severity ASC NULLS LAST, fixed_version COLLATE pg_catalog."default" ASC NULLS LAST, vulnerability_id COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_details_severity_vulnerability_id
                ON public.k_vuln_details USING btree
                (severity ASC NULLS LAST, vulnerability_id COLLATE pg_catalog."default" ASC NULLS LAST)
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
        Schema::dropIfExists('k_vuln_details');


        //k_pods
        $delete_sql[] = <<<SQL
            DROP SEQUENCE IF EXISTS public.vuln_details_id_seq
        SQL;

        foreach ($delete_sql as $sql ) {
            DB::statement($sql);
        }
    }
}