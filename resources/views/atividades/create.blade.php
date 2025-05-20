@extends('templates.layout')

@section('title', 'Criar Atividade')

@section('content')
<div class="container py-4">
    <h2>Criar Nova Atividade</h2>

    <form action="{{ route('atividades.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
