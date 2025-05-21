@extends('templates.layout')

@section('title', 'Atividades Entregues')

@section('content')
<div class="container py-4">
    <h2>Atividades Entregues</h2>

    @if ($entregas->isEmpty())
        <p>Você ainda não entregou nenhuma atividade.</p>
    @else
        <div class="row">
            @foreach ($entregas as $entrega)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">
                            {{ $entrega->atividade->titulo }}
                        </div>
                        <div class="card-body">
                            <p>{{ $entrega->atividade->descricao }}</p>
                            <p>
                                <strong>Arquivo entregue:</strong>
                                <a href="{{ asset('storage/' . $entrega->arquivo) }}" target="_blank">Ver arquivo</a>
                            </p>
                            <p>
                                <strong>Nota:</strong>
                                @if ($entrega->nota !== null)
                                    {{ $entrega->nota }}
                                @else
                                    <span class="text-muted">Ainda não avaliada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
