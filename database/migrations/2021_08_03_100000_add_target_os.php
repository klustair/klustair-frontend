<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetOs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_target_trivy ADD COLUMN IF NOT EXISTS isOS boolean
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
            ALTER TABLE public.k_target_trivy DROP COLUMN isOS;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
