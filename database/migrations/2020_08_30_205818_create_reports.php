<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE public.k_reports
            (
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                checktime timestamp with time zone NOT NULL,
                CONSTRAINT k_reports_test_pkey PRIMARY KEY (uid),
                CONSTRAINT k_reports_test_uid_key UNIQUE (uid)
            )
            WITH (
                OIDS = FALSE
            )
            TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            ALTER TABLE public.k_reports
                OWNER to postgres;
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
        Schema::dropIfExists('k_reports');
    }
}
