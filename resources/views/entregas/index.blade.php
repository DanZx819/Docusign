@extends('templates.layout')

@section('title', 'Entregas dos Alunos')

@section('content')
<div class="container py-4">
    <h2>Entregas dos Alunos</h2>

    @foreach ($entregas as $entrega)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $entrega->atividade->titulo }}</h5>
                <p class="card-text">
                    Aluno: {{ $entrega->user->name }}<br>
                    Arquivo:
                    @if ($entrega->arquivo)
                        <a href="{{ asset('storage/' . $entrega->arquivo) }}" target="_blank" class="btn btn-secondary py-1 px-3">Visualizar</a>
                    @else
                        Nenhum arquivo enviado
                    @endif
                </p>

                <form action="{{ route('entregas.nota', $entrega->id) }}" method="POST" class="row g-2 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <input type="number" name="nota" step="0.1" min="0" max="10" class="form-control" value="{{ $entrega->nota }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Salvar Nota</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
