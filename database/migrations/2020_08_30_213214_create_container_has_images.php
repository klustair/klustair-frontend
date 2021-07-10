<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerHasImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_container_has_images
            (
                report_uid character varying COLLATE pg_catalog."default" NOT NULL,
                container_uid character varying COLLATE pg_catalog."default" NOT NULL,
                image_uid character varying COLLATE pg_catalog."default" NOT NULL,
                CONSTRAINT k_container_has_images_pkey PRIMARY KEY (container_uid, report_uid),
                CONSTRAINT k_container_has_images_report_uid_fkey FOREIGN KEY (report_uid)
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

        if(getenv("DB_USERNAME") !== false){
            $create_sql[] = <<<SQL
            ALTER TYPE public.k_container_has_images
                OWNER to getenv('DB_USERNAME');
            SQL;
        }

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_container_has_images_report_uid_fkey
                ON public.k_container_has_images USING btree
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
        Schema::dropIfExists('k_container_has_images');
    }
}
