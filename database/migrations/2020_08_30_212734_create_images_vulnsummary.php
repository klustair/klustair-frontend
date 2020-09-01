<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesVulnsummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE public.k_images_vulnsummary
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                severity vulnerability_severities,
                total integer,
                fixed integer,
                report_uid character varying COLLATE pg_catalog."default",
                image_uid character varying COLLATE pg_catalog."default",
                CONSTRAINT "k_imageVulnSummary_pkey" PRIMARY KEY (uid),
                CONSTRAINT k_images_vulnsummary_report_uid_fkey FOREIGN KEY (report_uid)
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
            ALTER TABLE public.k_images_vulnsummary
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX k_images_vulnsummary_report_uid_fkey
                ON public.k_images_vulnsummary USING btree
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
        Schema::dropIfExists('k_images_vulnsummary');
    }
}
