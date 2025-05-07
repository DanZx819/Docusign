@extends('templates.layout')

@section('content')
<div class="container">
    <h2 class="mb-4">Arquivos Enviados</h2>

    <div class="row">
        @forelse ($files as $file)
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->title }}</h5>
                        <p class="card-text">{{ $file->description }}</p>

                        <!-- Botão de download -->
                        <a href="{{ route('files.download', ['filename' => $file->filename]) }}" class="btn btn-primary mb-2">
                            Baixar PDF
                        </a>

                        <!-- Botão de confirmação de assinatura -->
                        <form action="{{ route('files.confirm', ['id' => $file->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success mb-2">Confirmar Assinatura</button>
                        </form>

                        <!-- Botão de negação de assinatura -->
                        <form action="{{ route('files.reject', ['id' => $file->id]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger mb-2">Negar Assinatura</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum arquivo disponível.</p>
        @endforelse
    </div>
</div>
@endsection
