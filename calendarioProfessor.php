<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar se o usuário está logado e é professor
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 'professor') {
    header("Location: login.php");
    exit();
}

include_once('config.php');

// Buscar dados do professor
$professor_id = $_SESSION['usuario_id'];
$query_professor = "SELECT * FROM usuarios WHERE id = ?";
$stmt_professor = mysqli_prepare($conexao, $query_professor);
mysqli_stmt_bind_param($stmt_professor, "i", $professor_id);
mysqli_stmt_execute($stmt_professor);
$result_professor = mysqli_stmt_get_result($stmt_professor);
$professor = mysqli_fetch_assoc($result_professor);

// Extrair iniciais para o avatar
$iniciais = '';
$nomes = explode(' ', $professor['nome']);
if (count($nomes) > 0) {
    $iniciais = strtoupper(substr($nomes[0], 0, 1));
    if (count($nomes) > 1) {
        $iniciais .= strtoupper(substr(end($nomes), 0, 1));
    }
}

// Processar notas do calendário (via POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['data_selecionada'])) {
    $data = $_POST['data_selecionada'];
    $nota = $_POST['nota'];
    
    // Verificar se já existe nota para esta data
    $query = "SELECT id FROM notas_calendario WHERE professor_id = ? AND data = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "is", $professor_id, $data);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        $query = "UPDATE notas_calendario SET nota = ? WHERE professor_id = ? AND data = ?";
    } else {
        $query = "INSERT INTO notas_calendario (nota, professor_id, data) VALUES (?, ?, ?)";
    }
    
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "sis", $nota, $professor_id, $data);
    $success = mysqli_stmt_execute($stmt);
    
    // Retornar resposta JSON para AJAX
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'nota' => htmlspecialchars($nota)
    ]);
    exit();
}

// Buscar notas do professor
$query_notas = "SELECT data, nota FROM notas_calendario WHERE professor_id = ?";
$stmt_notas = mysqli_prepare($conexao, $query_notas);
mysqli_stmt_bind_param($stmt_notas, "i", $professor_id);
mysqli_stmt_execute($stmt_notas);
$result_notas = mysqli_stmt_get_result($stmt_notas);

$notas = [];
while ($row = mysqli_fetch_assoc($result_notas)) {
    $notas[$row['data']] = htmlspecialchars($row['nota']);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Agenda | YoungCash</title>

    <!-- FontAwesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" />

    <style>
        :root {
            --primary: #FFD700;
            --secondary: #FFA500;
            --dark: #1A1A1A;
            --light: #FFFFFF;
            --gray: #F5F5F5;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: var(--dark);
        }

        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: var(--dark);
            color: var(--light);
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--primary);
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin: 0 auto 15px;
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary);
        }

        .profile-role {
            font-size: 14px;
            color: #aaa;
        }

        .nav-menu {
            margin-top: 40px;
        }

        .nav-item {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            color: #ddd;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255, 215, 0, 0.1);
            color: var(--primary);
        }

        .nav-item.active {
            background: rgba(255, 215, 0, 0.2);
            color: var(--primary);
        }

        .nav-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            padding: 40px;
            background-color: var(--gray);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--dark);
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Calendário */
        #calendar {
            background: var(--light);
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        /* Modal de Notas */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: var(--light);
            padding: 30px;
            border-radius: 10px;
            width: 500px;
            max-width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .close-modal:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            resize: vertical;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--dark);
            border: none;
        }

        .btn-primary:hover {
            background: #FFC700;
        }

        .btn-secondary {
            background: #f0f0f0;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .main-content {
                padding: 20px;
            }
        }

        /* Estilo para eventos com notas */
        .fc-event.has-note {
            cursor: pointer;
            position: relative;
        }

        .fc-event.has-note::after {
            content: '📝';
            margin-left: 5px;
        }

        .note-indicator {
            display: inline-block;
            margin-left: 5px;
            color: var(--primary);
        }

        /* Tooltip personalizado */
        .fc-event-tooltip {
            position: absolute;
            background: #333;
            color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            z-index: 10000;
            white-space: nowrap;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
    <div class="profile">
        <div class="avatar">PR</div>
        <div class="profile-name">Prof. Rafael</div>
        <div class="profile-role">Professor</div>
    </div>
    <nav class="nav-menu">
        <a href="homeProfessor.php" class="nav-item">
            <i class="fa fa-home"></i> Início
        </a>
        <a href="agenda.php" class="nav-item active">
            <i class="fa fa-calendar"></i> Agenda
        </a>
        
        <a href="notas.php" class="nav-item">
            <i class="fa fa-clipboard"></i> Notas
        </a>
        <a href="sair.php" class="nav-item">
            <i class="fa fa-sign-out-alt"></i> Sair
        </a>
    </nav>
</aside>


        <!-- Conteúdo principal -->
        <main class="main-content">
            <header class="header">
                <h1><i class="fa fa-calendar"></i> Agenda</h1>
            </header>

            <div id="calendar"></div>
        </main>
    </div>

    <!-- Modal para notas -->
    <div id="modal-notas" class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">Adicionar / Editar Nota</h2>
                <button class="close-modal" aria-label="Fechar modal">&times;</button>
            </div>
            <form id="form-nota">
                <div class="form-group">
                    <label for="nota-text">Nota para <span id="data-selecionada"></span>:</label>
                    <textarea id="nota-text" name="nota" class="form-control" placeholder="Digite sua nota aqui..."></textarea>
                </div>
                <input type="hidden" id="data-input" name="data_selecionada" />
                <div class="btn-group" style="justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary close-modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('modal-notas');
            const closeModalButtons = modal.querySelectorAll('.close-modal');
            const formNota = document.getElementById('form-nota');
            const notaText = document.getElementById('nota-text');
            const dataSelecionadaSpan = document.getElementById('data-selecionada');
            const dataInput = document.getElementById('data-input');

            // Notas do servidor (PHP -> JS)
            const notas = <?= json_encode($notas) ?>;

            // Preparar eventos para o calendário
            const eventos = Object.keys(notas).map(data => ({
                title: '📝 Nota',
                start: data,
                allDay: true,
                extendedProps: {
                    nota: notas[data]
                },
                classNames: ['has-note']
            }));

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: eventos,
                dateClick: function (info) {
                    openModal(info.dateStr);
                },
                eventClick: function (info) {
                    openModal(info.event.startStr, info.event.extendedProps.nota);
                }
            });

            calendar.render();

            // Função para abrir modal e carregar nota se existir
            function openModal(data, nota = '') {
                dataSelecionadaSpan.textContent = data;
                dataInput.value = data;
                notaText.value = nota;
                modal.style.display = 'flex';
                notaText.focus();
            }

            // Fechar modal
            closeModalButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    modal.style.display = 'none';
                });
            });

            // Enviar nota via AJAX
            formNota.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(formNota);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Atualizar evento no calendário
                            const eventDate = data.data;
                            const nota = data.nota;

                            // Remover evento antigo com essa data
                            const eventsToRemove = calendar.getEvents().filter(ev => ev.startStr === eventDate);
                            eventsToRemove.forEach(ev => ev.remove());

                            if (nota.trim() !== '') {
                                // Adicionar novo evento com a nota atualizada
                                calendar.addEvent({
                                    title: '📝 Nota',
                                    start: eventDate,
                                    allDay: true,
                                    extendedProps: { nota: nota },
                                    classNames: ['has-note']
                                });
                            }

                            modal.style.display = 'none';
                        } else {
                            alert('Erro ao salvar a nota.');
                        }
                    })
                    .catch(() => alert('Erro na comunicação com o servidor.'));
            });

            // Fechar modal ao clicar fora do conteúdo
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
