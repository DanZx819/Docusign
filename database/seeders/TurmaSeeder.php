<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turma;

class TurmaSeeder extends Seeder
{
    public function run(): void
    {
        Turma::create(['name' => 'Turma A']);
        Turma::create(['name' => 'Turma B']);
    }
}

