<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título do contrato
            $table->text('description')->nullable(); // Descrição (opcional)
            $table->string('file_path'); // Caminho do arquivo PDF
            $table->timestamps(); // Registra created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
