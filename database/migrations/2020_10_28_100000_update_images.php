<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_images ADD COLUMN IF NOT EXISTS image_b64 character varying COLLATE pg_catalog."default"
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
            ALTER TABLE public.k_images DROP COLUMN image_b64;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
