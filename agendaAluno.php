<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o usuário está logado e é aluno
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'estudante') {
    header("Location: login.php");
    exit();
}

include_once('config.php');

if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

// Buscar dados do aluno
$aluno_id = $_SESSION['usuario_id'];
$query_aluno = "SELECT * FROM usuarios WHERE id = ?";
$stmt_aluno = mysqli_prepare($conexao, $query_aluno);


if (!$stmt_aluno) {
    die("Erro na preparação da consulta: " . mysqli_error($conexao));
}

if (!mysqli_stmt_bind_param($stmt_aluno, "i", $aluno_id)) {
    die("Erro ao vincular parâmetros: " . mysqli_stmt_error($stmt_aluno));
}

if (!mysqli_stmt_execute($stmt_aluno)) {
    die("Erro ao executar consulta: " . mysqli_stmt_error($stmt_aluno));
}

$result_aluno = mysqli_stmt_get_result($stmt_aluno);
if (!$result_aluno) {
    die("Erro ao obter resultado: " . mysqli_error($conexao));
}

$aluno = mysqli_fetch_assoc($result_aluno);
if (!$aluno) {
    die("Aluno não encontrado");
}

// Extrair iniciais para o avatar
$iniciais = '';
$nomes = explode(' ', $aluno['nome']);
if (count($nomes) > 0) {
    $iniciais = strtoupper(substr($nomes[0], 0, 1));
    if (count($nomes) > 1) {
        $iniciais .= strtoupper(substr(end($nomes), 0, 1));
    }
}

// Buscar eventos do aluno
$query_eventos = "SELECT id, nome AS titulo, data_evento FROM eventos";
$stmt_eventos = mysqli_prepare($conexao, $query_eventos);

if (!$stmt_eventos) {
    die("Erro na preparação da consulta de eventos: " . mysqli_error($conexao));
}

if (!mysqli_stmt_execute($stmt_eventos)) {
    die("Erro ao executar consulta: " . mysqli_stmt_error($stmt_eventos));
}

$result_eventos = mysqli_stmt_get_result($stmt_eventos);
if (!$result_eventos) {
    die("Erro ao obter resultado: " . mysqli_error($conexao));
}

$eventos_calendario = [];
while ($evento = mysqli_fetch_assoc($result_eventos)) {
    $eventos_calendario[] = [
        'id' => $evento['id'],
        'title' => $evento['titulo'],
        'start' => $evento['data_evento'],
        'end' => $evento['data_evento'],
        'description' => '',
        'color' => '#FFD700',
        'extendedProps' => [
            'curso' => 'Evento'
        ]
    ];
}
?>

<!-- HTML está acima (mantido como você enviou) -->

<!-- CONTINUAÇÃO DO SCRIPT FINAL -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const modal = document.getElementById('modal-evento');
        const closeButtons = document.querySelectorAll('.close-modal');

        // Eventos vindos do PHP
        const eventos = <?= json_encode($eventos_calendario) ?>;

        // Inicialização do calendário
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: eventos,
            eventClick: function (info) {
                document.getElementById('evento-titulo').textContent = info.event.title;
                document.getElementById('evento-curso').textContent = info.event.extendedProps.curso;
                document.getElementById('evento-data').textContent = info.event.start.toLocaleString('pt-BR');
                document.getElementById('evento-descricao').textContent = info.event.extendedProps.description || 'Sem descrição';
                modal.style.display = 'flex';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        editable: false,
        eventLimit: true,
        events: 'buscar_eventos.php' // <- puxando via AJAX
    });

    calendar.render();

    // Atualiza automaticamente a cada 30 segundos
    setInterval(() => {
        calendar.refetchEvents();
    }, 30000);
});

       

        // Fechar modal ao clicar no botão
        closeButtons.forEach(button => {
            button.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        });

        // Fechar modal ao clicar fora
        window.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
