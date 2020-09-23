<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContainersStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS ready boolean;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS started boolean;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS restart_count integer;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers ADD COLUMN IF NOT EXISTS started_at character varying;
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
            ALTER TABLE public.k_containers DROP COLUMN ready;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers DROP COLUMN started;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers DROP COLUMN restart_count;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers DROP COLUMN started_at;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
