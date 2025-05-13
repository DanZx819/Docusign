<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('files', function (Blueprint $table) {
        $table->unsignedBigInteger('turma_id')->nullable(); // Adiciona a coluna turma_id
        $table->foreign('turma_id')->references('id')->on('turmas')->onDelete('set null'); // Cria a chave estrangeira para a tabela turmas
    });
}

public function down()
{
    Schema::table('files', function (Blueprint $table) {
        $table->dropForeign(['turma_id']);
        $table->dropColumn('turma_id');
    });
}

};
