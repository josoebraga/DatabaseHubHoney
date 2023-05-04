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
        Schema::dropIfExists('lista_de_clientes_vip');
        Schema::create('lista_de_clientes_vip', function (Blueprint $table) {
            $table->id();
            $table->string('NOME')->nullable();
            $table->string('CPF_CNPJ')->nullable();
            $table->string('TELEFONE')->nullable();

            $table->string('ENDERECO')->nullable();
            $table->string('NUMERO')->nullable();
            $table->string('COMPLEMENTO')->nullable();
            $table->string('CIDADE')->nullable();
            $table->string('ESTADO')->nullable();
            $table->string('CEP')->nullable();

            $table->string('EMAIL')->nullable();

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

        try {
            DB::statement('DROP MATERIALIZED VIEW view_lista_de_clientes_vip');
        } catch (Exception $e) {
        }
        try {
            Schema::dropIfExists('lista_de_clientes_vip');
        } catch (Exception $e) {
            #sleep(5);
            #Schema::dropIfExists('lista_de_clientes_vip');
        }
    }
};
