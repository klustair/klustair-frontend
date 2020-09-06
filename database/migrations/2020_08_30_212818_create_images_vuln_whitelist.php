<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesVulnWhitelist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE public.k_images_vuln_whitelist
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                images_vuln_uid character varying COLLATE pg_catalog."default",
                image_uid character varying COLLATE pg_catalog."default",
                whitelisttime timestamp with time zone NOT NULL,
                message_txt text COLLATE pg_catalog."default",
                CONSTRAINT k_images_vuln_whitelist_pkey PRIMARY KEY (uid)
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $dbuser = env('DB_USERNAME', 'anchoreengine');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images_vuln_whitelist
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
        Schema::dropIfExists('k_images_vuln_whitelist');
    }
}
