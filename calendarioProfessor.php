<?php
// Ativar exibição de erros para desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

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

// Processar notas do calendário
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
        'nota' => htmlspecialchars($nota) // Proteção contra XSS
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
    $notas[$row['data']] = htmlspecialchars($row['nota']); // Proteção contra XSS
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda | YoungCash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
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
            background-color: #f5f5f5;
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
            background: white;
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
            background: white;
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
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
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
            z-index: 100;
            max-width: 250px;
            font-size: 14px;
            pointer-events: none;
            transform: translateX(-50%);
            bottom: calc(100% + 5px);
            left: 50%;
            display: none;
        }

        .fc-event:hover .fc-event-tooltip {
            display: block;
        }

        /* Melhorias na responsividade do calendário */
        .fc-toolbar-title {
            font-size: 1.2em;
        }
        
        .fc-button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <div class="avatar"><?= htmlspecialchars($iniciais) ?></div>
                <div class="profile-name"><?= htmlspecialchars($professor['nome']) ?></div>
                <div class="profile-role">Professor</div>
            </div>
            
            <div class="nav-menu">
                <a href="monitoramento.php" class="nav-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Monitoramento</span>
                </a>
                <a href="cursos.php" class="nav-item">
                    <i class="fas fa-book"></i>
                    <span>Meus Cursos</span>
                </a>
                <a href="agenda.php" class="nav-item active">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Agenda</span>
                </a>
                <a href="configuracoes.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Configurações</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-calendar-alt"></i> Agenda</h1>
            </div>
            
            <div id="calendar"></div>
        </div>
    </div>
    
    <!-- Modal para Adicionar Nota -->
    <div class="modal" id="noteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalDateTitle">Adicionar Nota</h3>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <form id="noteForm" method="POST">
                <input type="hidden" id="data_selecionada" name="data_selecionada">
                <div class="form-group">
                    <label for="nota">Nota para este dia:</label>
                    <textarea id="nota" name="nota" class="form-control" required></textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" id="cancelNote">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const noteModal = document.getElementById('noteModal');
            const modalTitle = document.getElementById('modalDateTitle');
            const dateInput = document.getElementById('data_selecionada');
            const noteInput = document.getElementById('nota');
            const noteForm = document.getElementById('noteForm');
            const closeModal = document.getElementById('closeModal');
            const cancelNote = document.getElementById('cancelNote');
            
            // Objeto para armazenar notas em memória
            const notasData = {
                <?php foreach ($notas as $data => $nota): ?>
                    '<?= $data ?>': `<?= addslashes($nota) ?>`,
                <?php endforeach; ?>
            };
            
            // Inicializar calendário
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                dateClick: function(info) {
                    const dateStr = info.dateStr;
                    const dateObj = new Date(dateStr);
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    const formattedDate = dateObj.toLocaleDateString('pt-BR', options);
                    
                    // Configurar modal
                    modalTitle.textContent = 'Nota para ' + formattedDate;
                    dateInput.value = dateStr;
                    
                    // Carregar nota existente (se houver)
                    if (notasData[dateStr]) {
                        noteInput.value = notasData[dateStr];
                    } else {
                        noteInput.value = '';
                    }
                    
                    // Mostrar modal
                    noteModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                },
                eventDidMount: function(info) {
                    const nota = info.event.extendedProps.nota || notasData[info.event.startStr];
                    if (nota) {
                        // Adicionar tooltip
                        const tooltip = document.createElement('div');
                        tooltip.className = 'fc-event-tooltip';
                        tooltip.textContent = nota;
                        info.el.appendChild(tooltip);
                        
                        // Adicionar classe para estilização
                        info.el.classList.add('has-note');
                    }
                },
                events: [
                    <?php foreach ($notas as $data => $nota): ?>
                    {
                        title: 'Nota',
                        start: '<?= $data ?>',
                        color: '#FFD700',
                        textColor: '#000000',
                        extendedProps: {
                            nota: `<?= addslashes($nota) ?>`
                        }
                    },
                    <?php endforeach; ?>
                ]
            });
            
            calendar.render();
            
            // Fechar modal
            function closeModalHandler() {
                noteModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            closeModal.addEventListener('click', closeModalHandler);
            cancelNote.addEventListener('click', closeModalHandler);
            
            // Fechar modal ao clicar fora
            noteModal.addEventListener('click', function(e) {
                if (e.target === noteModal) {
                    closeModalHandler();
                }
            });
            
            // Atualizar o calendário após salvar uma nota
            noteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const dateStr = formData.get('data_selecionada');
                const nota = formData.get('nota');
                
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na requisição');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Atualizar a nota em memória
                        notasData[dateStr] = nota;
                        
                        // Remover evento existente para esta data (se houver)
                        calendar.getEvents().forEach(event => {
                            if (event.startStr === dateStr) {
                                event.remove();
                            }
                        });
                        
                        // Adicionar novo evento apenas se houver nota
                        if (nota.trim() !== '') {
                            calendar.addEvent({
                                title: 'Nota',
                                start: dateStr,
                                color: '#FFD700',
                                textColor: '#000000',
                                extendedProps: {
                                    nota: nota
                                }
                            });
                        }
                        
                        // Fechar o modal
                        closeModalHandler();
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro ao salvar a nota.');
                });
            });
        });
    </script>
</body>
</html>