@extends('templates.layout')

@section('title', 'Enviar Feedback')

@section('content')
<div class="container mt-5">
    <h2>Enviar Novo Feedback</h2>

    <!-- Exibir mensagens de erro -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulário de feedback -->
    <form action="{{ route('feedbacks.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" id="titulo" required value="{{ old('titulo') }}">
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" id="descricao" rows="4" required>{{ old('descricao') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="avaliacao" class="form-label">Avaliação</label>
            <select name="avaliacao" class="form-select" id="avaliacao" required>
                <option value="">Selecione</option>
                <option value="boa" {{ old('avaliacao') == 'boa' ? 'selected' : '' }}>Boa</option>
                <option value="neutra" {{ old('avaliacao') == 'neutra' ? 'selected' : '' }}>Neutra</option>
                <option value="ruim" {{ old('avaliacao') == 'ruim' ? 'selected' : '' }}>Ruim</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enviar Feedback</button>
        <a href="#" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
