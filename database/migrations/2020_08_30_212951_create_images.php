<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE public.k_images
            (
                image_size bigint,
                layer_count bigint,
                uid character varying COLLATE pg_catalog."default",
                image_digest character varying COLLATE pg_catalog."default",
                fulltag character varying COLLATE pg_catalog."default",
                arch character varying(15) COLLATE pg_catalog."default",
                anchore_imageid character varying COLLATE pg_catalog."default",
                distro character varying COLLATE pg_catalog."default",
                distro_version character varying COLLATE pg_catalog."default",
                created_at timestamp without time zone,
                analyzed_at timestamp without time zone,
                registry character varying COLLATE pg_catalog."default",
                repo character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                dockerfile text COLLATE pg_catalog."default",
                CONSTRAINT k_images_report_uid_fkey FOREIGN KEY (report_uid)
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
            ALTER TABLE public.k_images
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX k_images_report_uid_fkey
                ON public.k_images USING btree
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
        Schema::dropIfExists('k_images');
    }
}
