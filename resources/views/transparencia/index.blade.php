@extends('templates.layout')

@section('title', 'Transparência Pública')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Transparência Pública</h1>

    <div class="card">
        <div class="card-header">
            Lista de Instituições
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Instituição</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>{{ $item->instituicao }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">
                                Nenhuma instituição encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
