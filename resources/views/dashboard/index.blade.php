@extends('templates.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">
    <h2>Dashboard do Usuário</h2>

    <div class="row">
        <div class="col-md-6">
            <canvas id="mediaNotasChart"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="tarefasConcluidasChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const totalAtividades = {{ $totalAtividades }};
    const tarefasConcluidas = {{ $tarefasConcluidas }};
    const mediaNotas = {{ $mediaNotas }};

    // Média das notas
    const ctxNotas = document.getElementById('mediaNotasChart').getContext('2d');
    const mediaNotasChart = new Chart(ctxNotas, {
        type: 'bar',
        data: {
            labels: ['Média das Notas'],
            datasets: [{
                label: 'Nota',
                data: [mediaNotas],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                maxBarThickness: 100
            }]
        },
        options: {
            scales: {
                y: {
                    min: 0,
                    max: 10,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Quantidade de tarefas concluídas
    const ctxTarefas = document.getElementById('tarefasConcluidasChart').getContext('2d');
    const tarefasConcluidasChart = new Chart(ctxTarefas, {
        type: 'doughnut',
        data: {
            labels: ['Concluídas', 'Pendentes'],
            datasets: [{
                data: [tarefasConcluidas, totalAtividades - tarefasConcluidas],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
