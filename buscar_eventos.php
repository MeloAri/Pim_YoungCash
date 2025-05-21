<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado e é estudante
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'estudante') {
    http_response_code(403);
    echo json_encode(['error' => 'Acesso não autorizado']);
    exit();
}

include_once('config.php');

if (!$conexao) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na conexão com o banco de dados']);
    exit();
}

// Busca os eventos (para todos os alunos)
$query_eventos = "SELECT id, nome AS titulo, data_evento FROM eventos";
$stmt_eventos = mysqli_prepare($conexao, $query_eventos);

if (!$stmt_eventos) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na consulta de eventos']);
    exit();
}

if (!mysqli_stmt_execute($stmt_eventos)) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao executar consulta']);
    exit();
}

$result_eventos = mysqli_stmt_get_result($stmt_eventos);
$eventos = [];

while ($evento = mysqli_fetch_assoc($result_eventos)) {
    $eventos[] = [
        'id' => $evento['id'],
        'title' => $evento['titulo'],
        'start' => $evento['data_evento'],
        'end' => $evento['data_evento'],
        'color' => '#FFD700',
        'extendedProps' => [
            'curso' => 'Evento'
        ]
    ];
}

echo json_encode($eventos);