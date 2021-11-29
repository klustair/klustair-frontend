<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class V050 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //k_containers
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS image_id character varying COLLATE pg_catalog."default"
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS actual boolean
        SQL;

        //k_images
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images DROP COLUMN anchore_imageid;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images ADD COLUMN IF NOT EXISTS config json
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images ADD COLUMN IF NOT EXISTS history json
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
        //k_containers
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers DROP COLUMN image_id;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers DROP COLUMN actual;
        SQL;

        //k_images
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images ADD COLUMN IF NOT EXISTS anchore_imageid character varying COLLATE pg_catalog."default"
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images DROP COLUMN config;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images DROP COLUMN history;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
