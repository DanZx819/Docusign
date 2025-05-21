@extends('templates.layout')

@section('title', 'Atividades Corrigidas')

@section('content')
<div class="container py-4">
    <h2>Atividades Corrigidas</h2>

    @forelse ($entregas as $entrega)
        <div class="card mb-3">
            <div class="card-header">
                {{ $entrega->atividade->titulo }} 
            </div>
            <div class="card-body">
                <p><strong>Aluno:</strong> {{ $entrega->user->name }}</p>

                <p><strong>Arquivo:</strong>
                    <a href="{{ asset('storage/' . $entrega->arquivo) }}" target="_blank">Ver Arquivo</a>
                </p>
                <p><strong>Nota: {{ $entrega->nota }} </strong> </p>
            </div>
        </div>
    @empty
        <p>Nenhuma atividade corrigida ainda.</p>
    @endforelse
</div>
@endsection
