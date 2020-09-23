<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesVuln extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_images_vuln
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                image_uid character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                feed character varying COLLATE pg_catalog."default",
                feed_group character varying COLLATE pg_catalog."default",
                fix character varying COLLATE pg_catalog."default",
                nvd_data_id character varying COLLATE pg_catalog."default",
                nvd_data_base_score double precision,
                nvd_data_exploitability_score double precision,
                nvd_data_impact_score double precision,
                package_fullname character varying COLLATE pg_catalog."default",
                package_cpe character varying COLLATE pg_catalog."default",
                package_cpe23 character varying COLLATE pg_catalog."default",
                package_name character varying COLLATE pg_catalog."default",
                package_path character varying COLLATE pg_catalog."default",
                package_type character varying COLLATE pg_catalog."default",
                package_version character varying COLLATE pg_catalog."default",
                severity vulnerability_severities,
                url character varying COLLATE pg_catalog."default",
                vuln character varying COLLATE pg_catalog."default",
                CONSTRAINT k_images_vuln_pkey PRIMARY KEY (uid),
                CONSTRAINT k_images_vuln_report_uid_fkey FOREIGN KEY (report_uid)
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

        $dbuser = env('DB_USERNAME', 'anchoreengine');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images_vuln
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_images_vuln_report_uid_fkey
                ON public.k_images_vuln USING btree
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
        Schema::dropIfExists('k_images_vuln');
    }
}
