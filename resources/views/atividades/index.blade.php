@extends('templates.layout')

@section('title', 'Atividades')

@section('content')
<div class="container py-4">
    <h2>Atividades da Sua Turma</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($atividades as $atividade)
        <div class="card mb-3">
            <div class="card-header">
                {{ $atividade->titulo }}
            </div>
            <div class="card-body">
                <p>{{ $atividade->descricao }}</p>
                <small class="text-muted">Criado por: {{ $atividade->criador->name }}</small>
            </div>
        </div>
    @empty
        <p>Nenhuma atividade cadastrada para sua turma.</p>
    @endforelse
</div>
@endsection
