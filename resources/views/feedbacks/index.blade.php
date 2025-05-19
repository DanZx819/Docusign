@extends('templates.layout')

@section('title', 'Lista de Feedbacks')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Feedbacks Recebidos</h2>
        <a href="{{ route('feedbacks.create') }}" class="btn btn-primary">Novo Feedback</a>
    </div>

    <!-- Exemplo de lista de feedbacks -->
    <div class="list-group">
        @foreach ($feedbacks as $feedback)
            <div class="list-group-item">
                <h5 class="mb-1">{{ $feedback->titulo }} 
                    <span class="badge 
                        @if($feedback->avaliacao == 'boa') bg-success 
                        @elseif($feedback->avaliacao == 'neutra') bg-secondary 
                        @else bg-danger @endif
                    ">
                        {{ ucfirst($feedback->avaliacao) }}
                    </span>
                </h5>
                <p class="mb-1">{{ $feedback->descricao }}</p>
                <small>Enviado por: {{ $feedback->usuario->name ?? 'Desconhecido' }}</small>
            </div>
        @endforeach
    </div>
</div>
@endsection
