<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

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
            CREATE INDEX IF NOT EXISTS k_containers_namespace_uid
                ON public.k_containers USING btree
                (namespace_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_containers_image
                ON public.k_containers USING btree
                (image COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_reports_checktime
                ON public.k_reports USING btree
                (checktime ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_vulnwhitelist_wl_vuln
                ON public.k_vulnwhitelist USING btree
                (wl_vuln ASC NULLS LAST)
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
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_vuln_trivy_vulnerability_id;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_vuln_trivy_image_uid;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_vuln_trivy_severity;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_reports_checktime;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_containers_namespace_uid;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_containers_image;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_reports_checktime;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_vulnwhitelist_wl_vuln;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
