<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class V071 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        // add not_acknowledged to k_reports_summaries
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_vulnsummary ADD COLUMN IF NOT EXISTS not_acknowledged integer
        SQL;

        // add vuln_not_acknowledged to k_reports_summaries
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_reports_summaries ADD COLUMN IF NOT EXISTS vuln_not_acknowledged integer
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
            ALTER TABLE public.k_vulnsummary DROP COLUMN not_acknowledged;
        SQL;
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_reports_summaries DROP COLUMN vuln_not_acknowledged;
        SQL;

        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS k_vuln_trivy_severity_vulnerability_id;
        SQL;
        
        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
