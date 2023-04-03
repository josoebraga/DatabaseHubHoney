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
        Schema::create('importacao_dinamica', function (Blueprint $table) {
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
        Schema::dropIfExists('importacao_dinamica');
    }
};
