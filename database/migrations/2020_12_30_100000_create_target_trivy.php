<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetTrivy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_target_trivy
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                target character varying COLLATE pg_catalog."default",
                target_type character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                image_uid character varying COLLATE pg_catalog."default",
                CONSTRAINT k_target_trivy_pkey PRIMARY KEY (uid),
                CONSTRAINT k_target_trivy_report_uid_fkey FOREIGN KEY (report_uid)
                    REFERENCES public.k_reports (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE CASCADE
            )
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_target_trivy_report_uid_fkey
                ON public.k_target_trivy USING btree
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
        Schema::dropIfExists('k_target_trivy');
    }
}
