@extends('templates.layout')

@section('title', 'Atividades')

@section('content')
<div class="container py-4">
    <h2>Atividades da Sua Turma</h2>


    @forelse ($atividades as $atividade)
        <div class="card mb-3">
            <div class="card-header">
                {{ $atividade->titulo }}
            </div>
            <div class="card-body">
                <p>{{ $atividade->descricao }}</p>
                <small class="text-muted">Criado por: {{ $atividade->criador->name }}</small>

                @if (session('role') !== 'admin')
                    <form action="{{ route('entregas.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <input type="hidden" name="atividade_id" value="{{ $atividade->id }}">

                        <div class="mb-2">
                            <label for="arquivo-{{ $atividade->id }}" class="form-label">Anexar arquivo (opcional)</label>
                            <input type="file" name="arquivo" id="arquivo-{{ $atividade->id }}" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success">Entregar Atividade</button>
                    </form>
                    @if ($user && $user->role === 'admin')
                        <form action="{{ route('atividades.destroy', $atividade->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar esta atividade?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mt-2">Deletar</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    @empty
        <p>Nenhuma atividade cadastrada para sua turma.</p>
    @endforelse
</div>
@endsection
