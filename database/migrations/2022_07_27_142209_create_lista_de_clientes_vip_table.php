<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::dropIfExists('lista_de_clientes_vip');
    }
};
