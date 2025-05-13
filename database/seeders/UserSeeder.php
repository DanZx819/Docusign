<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Models\Turma;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria ou recupera as turmas A e B
        $turmaA = Turma::firstOrCreate(['nome' => 'A']);
        $turmaB = Turma::firstOrCreate(['nome' => 'B']);

        // Criação dos usuários
        Users::create([
            'name' => 'Admin 1',
            'email' => 'admin1@example.com',
            'password' => Hash::make('admin1'),
            'role' => 'admin',
            'turma_id' => $turmaA->id,
        ]);

        Users::create([
            'name' => 'Admin 2',
            'email' => 'admin2@example.com',
            'password' => Hash::make('admin2'),
            'role' => 'admin',
            'turma_id' => $turmaB->id,
        ]);

        Users::create([
            'name' => 'Usuário 1',
            'email' => 'user1@example.com',
            'password' => Hash::make('user1'),
            'role' => 'user',
            'turma_id' => $turmaA->id,
        ]);

        Users::create([
            'name' => 'Usuário 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('user2'),
            'role' => 'user',
            'turma_id' => $turmaB->id,
        ]);
    }
}
