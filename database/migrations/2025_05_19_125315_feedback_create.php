<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->foreignId('usuario_id')->constrained('users'); // ou 'usuarios', se sua tabela de usuários for essa
            $table->enum('avaliacao', ['boa', 'neutra', 'ruim']);
            $table->timestamps(); // cria os campos created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
};
