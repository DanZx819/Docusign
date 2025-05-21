<?php 
namespace App\Http\Controllers;
use App\Models\Atividade;
use App\Models\Entrega;
use Illuminate\Http\Request;
class AtividadeController extends Controller
{
    
public function index()
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user) {
        return redirect()->route('login')->with('error', 'Usuário não autenticado.');
    }

    // Pega IDs das atividades que o usuário já entregou
    $atividadesEntreguesIds = Entrega::where('user_id', $user->id)
        ->pluck('atividade_id')
        ->toArray();

    // Lista só as atividades da turma que ele AINDA NÃO entregou
    $atividades = Atividade::where('turma_id', $user->turma_id)
        ->whereNotIn('id', $atividadesEntreguesIds)
        ->get();

    return view('atividades.index', compact('atividades', 'user'));
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
public function entregues()
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user) {
        return redirect()->route('login')->with('error', 'Usuário não autenticado.');
    }

    // Busca todas as entregas do usuário
    $entregas = Entrega::with('atividade')
        ->where('user_id', $user->id)
        ->get();

    return view('entregas.entregues', compact('entregas'));
}
public function destroy($id)
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user || $user->role !== 'admin') {
        return redirect()->back()->with('error', 'Ação não autorizada.');
    }

    $atividade = \App\Models\Atividade::findOrFail($id);
    $atividade->delete();

    return redirect()->back()->with('success', 'Atividade deletada com sucesso.');
}
public function corrigidas()
{
    $user = \App\Models\Users::find(session('user_id'));

    if (!$user) {
        return redirect()->route('login')->with('error', 'Usuário não autenticado.');
    }

    // Busca apenas entregas que já têm nota
    $entregasCorrigidas = \App\Models\Entrega::with('atividade')
        ->where('user_id', $user->id)
        ->whereNotNull('nota')
        ->get();

    return view('atividades.corrigidas', compact('entregasCorrigidas'));
}

}
