<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSugestoesToFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('sugestoes')->nullable()->after('descricao'); 
            // nullable porque pode não ser obrigatório
        });
    }

    public function down()
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('sugestoes');
        });
    }
}
