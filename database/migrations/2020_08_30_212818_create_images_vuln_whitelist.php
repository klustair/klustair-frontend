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
            CREATE TABLE IF NOT EXISTS public.k_vulnwhitelist
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                wl_vuln character varying COLLATE pg_catalog."default",
                wl_image_b64 character varying COLLATE pg_catalog."default",
                whitelisttime timestamp with time zone NOT NULL,
                message_txt text COLLATE pg_catalog."default",
                CONSTRAINT k_vulnwhitelist_pkey PRIMARY KEY (uid)
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        if(getenv("DB_USERNAME") !== false){
            $create_sql[] = <<<SQL
            ALTER TYPE public.k_vulnwhitelist
                OWNER to getenv('DB_USERNAME');
            SQL;
        }

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vulnwhitelist_wl_vuln
                ON public.k_vulnwhitelist USING btree
                (wl_vuln ASC NULLS LAST)
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
        Schema::dropIfExists('k_vulnwhitelist');
    }
}
