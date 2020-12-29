<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVulnTrivy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */ target_uid character varying COLLATE pg_catalog."default",
    public function up()
    {
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_vuln_trivy ADD COLUMN IF NOT EXISTS target_uid character varying COLLATE pg_catalog."default"
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
            ALTER TABLE public.k_vuln_trivy DROP COLUMN target_uid;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
