<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\Users;
use App\Models\Atividade;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Users::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não autenticado.');
        }

        // Total de atividades da turma do usuário
        $totalAtividades = Atividade::where('turma_id', $user->turma_id)->count();

        // Quantidade de atividades concluídas (entregues pelo usuário, 1 entrega por atividade)
        $tarefasConcluidas = Entrega::where('user_id', $user->id)
            ->distinct('atividade_id') // garante que conta uma vez por atividade
            ->count('atividade_id');

        // Média das notas
        $mediaNotas = Entrega::where('user_id', $user->id)
            ->whereNotNull('nota')
            ->avg('nota');

        return view('dashboard.index', [
            'mediaNotas' => $mediaNotas ?: 0,
            'tarefasConcluidas' => $tarefasConcluidas,
            'totalAtividades' => $totalAtividades,
        ]);
    }
}
