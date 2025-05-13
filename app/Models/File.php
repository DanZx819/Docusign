<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'title',
        'description',
        'filename',
        'status',
        'turma_id',  // Adicione 'turma_id' ao array fillable
    ];
}
