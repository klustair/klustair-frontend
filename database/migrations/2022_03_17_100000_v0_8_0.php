<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class V080 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_vuln_trivy
                RENAME TO k_vuln_trivy_old;
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
            ALTER TABLE IF EXISTS k_vuln_trivy_old
                RENAME TO k_vuln_trivy;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }

    }
}
