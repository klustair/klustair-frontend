<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateImagesVulnWhitelist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images_vuln_whitelist DROP COLUMN IF EXISTS wl_anchore_imageid;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images_vuln_whitelist ADD COLUMN IF NOT EXISTS wl_image_b64 character varying COLLATE pg_catalog."default"
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
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images_vuln_whitelist DROP COLUMN IF EXISTS wl_image_b64;
        SQL;
        $create_sql[] = <<<SQL
        ALTER TABLE public.k_images_vuln_whitelist ADD COLUMN IF NOT EXISTS wl_anchore_imageid character varying COLLATE pg_catalog."default"
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
