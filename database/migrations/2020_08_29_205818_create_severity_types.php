<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeverityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
        DO $$
        BEGIN
            IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vulnerability_severities') THEN
                CREATE TYPE public.vulnerability_severities AS ENUM
                    ('Unknown', 'Negligible', 'Low', 'Medium', 'High', 'Critical');
            END IF;
        END$$;
        SQL;

        if(getenv("DB_USERNAME") !== false){
            $create_sql[] = <<<SQL
            ALTER TYPE public.vulnerability_severities
                OWNER to getenv('DB_USERNAME');
        SQL;
        }

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
        $drop_sql[] = <<<SQL
            DROP TYPE public.vulnerability_severities;
        SQL;

        foreach ($drop_sql as $sql ) {
            DB::statement($sql);
        }
    }
}
