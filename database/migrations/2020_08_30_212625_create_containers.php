<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $create_sql[] = <<<SQL
            CREATE TABLE public.k_containers
            (
                name character varying COLLATE pg_catalog."default" NOT NULL,
                report_uid character varying COLLATE pg_catalog."default" NOT NULL,
                namespace_uid character varying COLLATE pg_catalog."default" NOT NULL,
                pod_uid character varying COLLATE pg_catalog."default" NOT NULL,
                image character varying COLLATE pg_catalog."default",
                image_pull_policy character varying COLLATE pg_catalog."default",
                security_context json,
                init_container boolean,
                uid character varying COLLATE pg_catalog."default",
                CONSTRAINT k_containers_pkey PRIMARY KEY (name, report_uid, namespace_uid, pod_uid),
                CONSTRAINT k_containers_uid_key UNIQUE (uid),
                CONSTRAINT k_containers_namespace_uid_fkey FOREIGN KEY (namespace_uid)
                    REFERENCES public.k_namespaces (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE NO ACTION
                    NOT VALID,
                CONSTRAINT k_containers_pod_uid_fkey FOREIGN KEY (pod_uid)
                    REFERENCES public.k_pods (uid) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE NO ACTION
                    NOT VALID,
                CONSTRAINT k_containers_report_uid_fkey FOREIGN KEY (report_uid)
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

        $dbuser = env('DB_USERNAME', 'anchoreengine');
        $create_sql[] = <<<SQL
            ALTER TABLE public.k_containers
                OWNER to $dbuser;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX k_containers_namespace_uid_fkey
                ON public.k_containers USING btree
                (namespace_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX k_containers_pod_uid_fkey
                ON public.k_containers USING btree
                (pod_uid COLLATE pg_catalog."default" ASC NULLS LAST)
                TABLESPACE pg_default;
        SQL;

        $create_sql[] = <<<SQL
            CREATE INDEX k_containers_report_uid_fkey
                ON public.k_containers USING btree
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
        Schema::dropIfExists('k_containers');
    }
}
