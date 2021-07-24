<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_audits
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                namespace_uid character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                audit_type character varying COLLATE pg_catalog."default",
                audit_name character varying COLLATE pg_catalog."default",
                msg text COLLATE pg_catalog."default",
                severity_level character varying COLLATE pg_catalog."default",
                audit_time timestamp with time zone NOT NULL,
                resource_name character varying COLLATE pg_catalog."default",
                capability character varying COLLATE pg_catalog."default",
                container character varying COLLATE pg_catalog."default",
                missing_annotation character varying COLLATE pg_catalog."default",
                resource_namespace character varying COLLATE pg_catalog."default",
                resource_api_version character varying COLLATE pg_catalog."default",
                CONSTRAINT k_audits_pkey PRIMARY KEY (uid),
                CONSTRAINT k_audits_uid_key UNIQUE (uid),
                CONSTRAINT k_audits_report_uid_fkey FOREIGN KEY (report_uid)
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

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_audits_report_uid_fkey
                ON public.k_audits USING btree
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
        Schema::dropIfExists('k_audits');
    }
}
