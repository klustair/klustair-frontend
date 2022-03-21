<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $create_sql[] = <<<SQL
            CREATE OR REPLACE VIEW public.count_no_ack_view
                AS
                SELECT k_images.report_uid,
                    k_images.uid as image_uid,
                    count(DISTINCT k_vuln.uid) AS vuln_ack_count
                FROM k_images
                    LEFT JOIN k_vuln ON k_images.uid::text = k_vuln.image_uid::text
                    LEFT JOIN k_vulnwhitelist ON k_vulnwhitelist.wl_image_b64::text = k_images.image_b64::text AND k_vulnwhitelist.wl_vuln::text = k_vuln.vulnerability_id::text
                WHERE k_vulnwhitelist.uid IS NULL
                GROUP BY k_images.uid, k_images.report_uid;
        SQL;

        $create_sql[] = <<<SQL
            CREATE OR REPLACE VIEW public.k_vuln_details_view
                AS
                SELECT 
                    uid, 
                    image_uid, 
                    report_uid, 
                    target_uid, 
                    kv.vulnerability_id, 
                    kv.pkg_name, 
                    descr, 
                    title, 
                    kv.installed_version, 
                    fixed_version, 
                    severity_source, 
                    severity, 
                    last_modified_date, 
                    published_date, 
                    links, 
                    cvss, 
                    cwe_ids 
                FROM k_vuln AS kv
                LEFT JOIN k_vuln_details AS kvd ON 
                    kv.vulnerability_id = kvd.vulnerability_id AND
                    kv.pkg_name = kvd.pkg_name
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
            DROP VIEW IF EXISTS public.count_no_ack_view;
        SQL;


        $create_sql[] = <<<SQL
            DROP VIEW IF EXISTS public.k_vuln_details_view;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
