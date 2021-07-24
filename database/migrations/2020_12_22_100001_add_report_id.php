<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE SEQUENCE IF NOT EXISTS public.reports_id_seq
                INCREMENT 1
                START 1
                MINVALUE 1
                MAXVALUE 2147483647
                CACHE 1;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_reports ADD COLUMN IF NOT EXISTS id integer NOT NULL DEFAULT nextval('reports_id_seq'::regclass)
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
            ALTER TABLE public.k_reports DROP COLUMN IF EXISTS id;
        SQL;
        $create_sql[] = <<<SQL
            DROP SEQUENCE IF EXISTS public.reports_id_seq;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
