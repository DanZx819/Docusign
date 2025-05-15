<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContratosTableRemoveFilePathAddFilename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Remover a coluna file_path
            $table->dropColumn('file_path');

            // Adicionar a coluna filename
            $table->string('filename', 255)->nullable();  // ou defina o tipo conforme necessário
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Adicionar a coluna file_path de volta caso precise reverter a migração
            $table->string('file_path')->nullable();

            // Remover a coluna filename
            $table->dropColumn('filename');
        });
    }
}
