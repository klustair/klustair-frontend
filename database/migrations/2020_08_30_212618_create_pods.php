<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE IF NOT EXISTS public.k_pods
            (
                podname character varying COLLATE pg_catalog."default" NOT NULL,
                report_uid character varying COLLATE pg_catalog."default" NOT NULL,
                namespace_uid character varying COLLATE pg_catalog."default" NOT NULL,
                uid character varying COLLATE pg_catalog."default" NOT NULL,
                creation_timestamp date NOT NULL,
                kubernetes_pod_uid character varying COLLATE pg_catalog."default",
                CONSTRAINT k_pods_pkey PRIMARY KEY (report_uid, namespace_uid, uid),
                CONSTRAINT k_pods_uid_key UNIQUE (uid),
                CONSTRAINT k_pods_namespace_uid_fkey FOREIGN KEY (namespace_uid)
                    REFERENCES public.k_namespaces (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE NO ACTION
                    NOT VALID,
                CONSTRAINT k_pods_report_uid_fkey FOREIGN KEY (report_uid)
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

        $dbuser = env('DB_USERNAME', 'postgres');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_pods
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_pods_namespace_uid_fkey
                ON public.k_pods USING btree
                (namespace_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;


        $create_sql[] = <<<SQL
            CREATE INDEX IF NOT EXISTS k_pods_report_uid_fkey
                ON public.k_pods USING btree
                (report_uid COLLATE pg_catalog."default" ASC NULLS LAST)
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
        Schema::dropIfExists('k_pods');
    }
}
