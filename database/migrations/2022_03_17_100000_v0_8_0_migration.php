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
        
        ### Migrate data to splitted Table
        $create_sql[] = <<<SQL
            INSERT INTO public.k_vuln (uid, image_uid, report_uid, target_uid, vulnerability_id, pkg_name, installed_version)
                SELECT 
                uid, image_uid, report_uid, target_uid, vulnerability_id, pkg_name, installed_version
                FROM k_vuln_trivy;
        SQL;

        $create_sql[] = <<<SQL
            INSERT INTO public.k_vuln_details (vulnerability_id, pkg_name, descr, title, installed_version, fixed_version, severity_source, severity, last_modified_date, published_date)
                SELECT 
                DISTINCT vulnerability_id, pkg_name, descr, title, installed_version, fixed_version, severity_source, severity, last_modified_date, published_date
                FROM k_vuln_trivy
        SQL;


        foreach ($create_sql as $sql ) {
            @DB::statement($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
