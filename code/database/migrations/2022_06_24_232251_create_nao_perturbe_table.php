<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('nao_perturbe');
        Schema::create('nao_perturbe', function (Blueprint $table) {
            $table->id();
            $table->string('ORIGEM')->nullable();
            $table->string('TELEFONE')->nullable();
            $table->string('DATA_BLOQUEIO')->nullable();
            $table->string('DATA_ARQUIVO')->nullable();
            $table->string('EMPRESA_BLOQUEADA')->nullable();
            $table->string('ORIGEM_DO_PEDIDO')->nullable();
            $table->string('DATA_PROCESSAMENTO')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        #DB::beginTransaction();
        try {
            DB::statement('DROP MATERIALIZED VIEW view_nao_perturbe');
        } catch (Exception $e) {
            #DB::statement("CREATE MATERIALIZED VIEW view_nao_perturbe AS SELECT * FROM public.view_nao_perturbe;");
            #DB::statement('DROP MATERIALIZED VIEW view_nao_perturbe');
        }
        try {
            Schema::dropIfExists('nao_perturbe');
            #DB::statement('DROP TABLE nao_perturbe');
        } catch (Exception $e) {
        }
        #DB::commit();
    }
};
