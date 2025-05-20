<?php 
namespace App\Http\Controllers;
use App\Models\Atividade;
use Illuminate\Http\Request;
class AtividadeController extends Controller
{
    public function index()
    {
        $user = \App\Models\Users::find(session('user_id'));

        if (!$user) {
            // Se não achar usuário na sessão, redireciona ou lança erro
            return redirect()->route('login')->with('error', 'Usuário não autenticado.');
        }

        // Pega as atividades da turma do usuário
        $atividades = Atividade::where('turma_id', $user->turma_id)->get();

        return view('atividades.index', compact('atividades'));
    }

    public function store(Request $request)
    {
        $user = \App\Models\Users::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuário não autenticado.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        Atividade::create([
            'user_id' => $user->id,
            'turma_id' => $user->turma_id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
        ]);

        return redirect()->route('atividades.index')->with('success', 'Atividade criada com sucesso.');
    }

    public function create()
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user || $user->role !== 'admin') {
        return redirect()->route('atividades.index')->with('error', 'Acesso negado.');
    }

    return view('atividades.create');
}

}
