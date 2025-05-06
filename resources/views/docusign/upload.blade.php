@extends('templates.layout')


@section('content')
<div class="container">
    <h2>Upload de PDFs</h2>

    <form action="{{ route('docusign.upload.pdf') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="pdfs[]" multiple accept="application/pdf">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>

    <hr>

    <h3>Documentos enviados</h3>

    <div class="row">
        @forelse ($files as $file)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>{{ $file }}</strong></p>
                        <a href="{{ route('docusign.sign', ['filename' => $file]) }}" class="btn btn-success">Assinar</a>
                    </div>
                </div>
            </div>
        @empty
            <p>Nenhum arquivo enviado ainda.</p>
        @endforelse
    </div>
</div>
@endsection

