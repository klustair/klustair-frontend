<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        k_images_vuln -> k_vuln_anchore
        */
        $create_sql[] = <<<SQL
        ALTER TABLE IF EXISTS k_images_vuln
        RENAME CONSTRAINT k_images_vuln_pkey TO k_vuln_anchore_pkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vuln
            RENAME CONSTRAINT k_images_vuln_report_uid_fkey TO k_vuln_anchore_report_uid_fkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vuln
            RENAME TO k_vuln_anchore;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_anchore_report_uid_fkey
                ON public.k_vuln_anchore USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS k_images_vuln_report_uid_fkey;
        SQL;


        /*
        k_images_trivyvuln -> k_vuln_trivy
        */

        $create_sql[] = <<<SQL
        ALTER TABLE IF EXISTS k_images_trivyvuln
        RENAME CONSTRAINT k_images_trivyvuln_pkey TO k_vuln_trivy_pkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_trivyvuln
            RENAME CONSTRAINT k_images_trivyvuln_report_uid_fkey TO k_vuln_trivy_report_uid_fkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_trivyvuln
            RENAME TO k_vuln_trivy;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_report_uid_fkey
                ON public.k_vuln_trivy USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS k_images_trivyvuln_report_uid_fkey;
        SQL;


        /*
        k_images_vulnsummary -> k_vulnsummary
        */


        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vulnsummary
            RENAME CONSTRAINT "k_imageVulnSummary_pkey" TO k_images_vulnsummary_pkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vulnsummary
            RENAME CONSTRAINT k_images_vulnsummary_report_uid_fkey TO k_vulnsummary_report_uid_fkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vulnsummary
            RENAME TO k_vulnsummary;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vulnsummary_report_uid_fkey
                ON public.k_vulnsummary USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;


        /*
        k_images_vuln_whitelist -> k_vulnwhitelist
        */

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vuln_whitelist
            RENAME CONSTRAINT k_images_vuln_whitelist_pkey TO k_vulnwhitelist_pkey;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE IF EXISTS k_images_vuln_whitelist
            RENAME TO k_vulnwhitelist;
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
        
    }
}
