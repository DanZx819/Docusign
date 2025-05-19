<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    
    protected $table = 'feedbacks';

    protected $fillable = [
        'titulo',
        'descricao',
        'avaliacao',
        'usuario_id',
        'sugestoes',
    ];

    public function usuario()
    {
        return $this->belongsTo(Users::class, 'usuario_id');
    }
}
