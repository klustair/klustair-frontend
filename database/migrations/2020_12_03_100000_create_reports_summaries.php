<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_reports_summaries
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                report_uid character varying COLLATE pg_catalog."default" NOT NULL,
                namespaces_checked integer,
                namespaces_total integer,
                vuln_total integer,
                vuln_critical integer,
                vuln_high integer,
                vuln_medium integer,
                vuln_low integer,
                vuln_unknown integer,
                vuln_fixed integer,
                pods integer,
                images integer,
                CONSTRAINT k_reports_summaries_pkey PRIMARY KEY (uid),
                CONSTRAINT k_reports_summaries_report_uid_fkey FOREIGN KEY (report_uid)
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
            ALTER TABLE public.k_reports_summaries
                OWNER to $dbuser;
        SQL;


        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_reports_summaries_report_uid_fkey
                ON public.k_reports_summaries USING btree
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
        Schema::dropIfExists('k_reports_summaries');
    }
}
