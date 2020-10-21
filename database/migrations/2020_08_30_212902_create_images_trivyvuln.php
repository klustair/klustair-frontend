<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTrivyvuln extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     * 
     * 
     * 
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_images_trivyvuln
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                image_uid character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                vulnerability_id character varying COLLATE pg_catalog."default",
                feed_group character varying COLLATE pg_catalog."default",
                pkg_name character varying COLLATE pg_catalog."default",
                descr character varying COLLATE pg_catalog."default",
                title character varying COLLATE pg_catalog."default",
                installed_version character varying COLLATE pg_catalog."default",
                fixed_version character varying COLLATE pg_catalog."default",
                severity_source character varying COLLATE pg_catalog."default",
                severity double precision,
                last_modified_date character varying COLLATE pg_catalog."default",
                published_date character varying COLLATE pg_catalog."default",
                ref character varying COLLATE pg_catalog."default",
                cvss character varying COLLATE pg_catalog."default",
                CONSTRAINT k_images_trivyvuln_pkey PRIMARY KEY (uid),
                CONSTRAINT k_images_trivyvuln_report_uid_fkey FOREIGN KEY (report_uid)
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
            ALTER TABLE public.k_images_trivyvuln
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_images_trivyvuln_report_uid_fkey
                ON public.k_images_trivyvuln USING btree
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
        Schema::dropIfExists('k_images_trivyvuln');
    }
}
