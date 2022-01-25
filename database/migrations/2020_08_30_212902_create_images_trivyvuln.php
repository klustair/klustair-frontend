<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTrivyvuln extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     * 
     * 
     * 
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_vuln_trivy
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                image_uid character varying COLLATE pg_catalog."default",
                report_uid character varying COLLATE pg_catalog."default",
                target_uid character varying COLLATE pg_catalog."default",
                vulnerability_id character varying COLLATE pg_catalog."default",
                pkg_name character varying COLLATE pg_catalog."default",
                descr character varying COLLATE pg_catalog."default",
                title character varying COLLATE pg_catalog."default",
                installed_version character varying COLLATE pg_catalog."default",
                fixed_version character varying COLLATE pg_catalog."default",
                severity_source character varying COLLATE pg_catalog."default",
                severity double precision,
                last_modified_date character varying COLLATE pg_catalog."default",
                published_date character varying COLLATE pg_catalog."default",
                links jsonb,
                cvss jsonb,
                cwe_ids jsonb,
                CONSTRAINT k_vuln_trivy_pkey PRIMARY KEY (uid),
                CONSTRAINT k_vuln_trivy_report_uid_fkey FOREIGN KEY (report_uid)
                    REFERENCES public.k_reports (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE CASCADE
                    NOT VALID
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_report_uid_fkey
                ON public.k_vuln_trivy USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_vulnerability_id
                ON public.k_vuln_trivy USING btree
                (vulnerability_id COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_image_uid
                ON public.k_vuln_trivy USING btree
                (image_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_severity
                ON public.k_vuln_trivy USING btree
                (severity ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_severity_vulnerability_id
                ON public.k_vuln_trivy USING btree
                (severity ASC NULLS LAST,
                vulnerability_id ASC NULLS LAST)
                TABLESPACE pg_default;
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
        Schema::dropIfExists('k_vuln_trivy');
    }
}
