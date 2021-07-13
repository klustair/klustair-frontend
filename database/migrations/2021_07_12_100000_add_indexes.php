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
            CREATE INDEX IF NOT EXISTS k_vuln_trivy_severity
                ON public.k_vuln_trivy USING btree
                (severity ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_images_fulltag
                ON public.k_images USING btree
                (fulltag ASC NULLS LAST)
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
            DROP INDEX IF EXISTS public.k_vuln_trivy_severity;
        SQL;
        $create_sql[] = <<<SQL
            DROP INDEX IF EXISTS public.k_vuln_trivy_k_vuln_trivy;
        SQL;

        foreach ($create_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
