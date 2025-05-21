<?php
session_start();

// Verificar se o usuário está logado como estudante
if (!isset($_SESSION['usuario_id'], $_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'estudante') {
    header("Location: /login.php");
    exit();
}

include_once('config.php');

$aluno_id = $_SESSION['usuario_id'];

// Buscar dados do aluno
$stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
if (!$stmt) {
    die("Erro no prepare (aluno): " . $conexao->error);
}
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result_aluno = $stmt->get_result();

if ($result_aluno->num_rows === 0) {
    die("Aluno não encontrado.");
}

$aluno = $result_aluno->fetch_assoc();
$stmt->close();

// Buscar cursos do aluno
$query_cursos = "
    SELECT 
        c.id, c.nome, c.descricao, c.categoria, c.total_aulas,
        u.nome AS professor_nome,
        ac.data_inscricao, ac.progresso, ac.aulas_concluidas
    FROM cursos c
    JOIN aluno_cursos ac ON c.id = ac.curso_id
    JOIN usuarios u ON c.professor_id = u.id
    WHERE ac.aluno_id = ?
";

$stmt = $conexao->prepare($query_cursos);
if (!$stmt) {
    die("Erro no prepare (cursos): " . $conexao->error);
}
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result_cursos = $stmt->get_result();
$stmt->close();

// Buscar estatísticas do aluno
$query_stats = "
    SELECT 
        COUNT(DISTINCT curso_id) AS total_cursos,
        IFNULL(SUM(aulas_concluidas), 0) AS total_aulas_concluidas,
        IFNULL(AVG(progresso), 0) AS media_progresso
    FROM aluno_cursos
    WHERE aluno_id = ?
";

$stmt = $conexao->prepare($query_stats);
if (!$stmt) {
    die("Erro no prepare (estatísticas): " . $conexao->error);
}
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result_stats = $stmt->get_result();

if ($result_stats === false) {
    die("Erro ao buscar estatísticas: " . $conexao->error);
}

$stats = $result_stats->fetch_assoc();
$stmt->close();

$total_cursos = (int)($stats['total_cursos'] ?? 0);
$total_aulas_concluidas = (int)($stats['total_aulas_concluidas'] ?? 0);
$media_progresso = round(floatval($stats['media_progresso'] ?? 0), 1);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Cursos | YoungCash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FFD700;
            --secondary: #FFA500;
            --dark: #1A1A1A;
            --light: #FFFFFF;
            --gray: #F5F5F5;
            --dark-gray: #333;
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
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .profile {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            margin-bottom: 10px;
        }
        
        .profile h3 {
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .profile p {
            font-size: 14px;
            color: #aaa;
        }
        
        .nav-menu {
            margin-top: 20px;
        }
        
        .nav-item {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            color: #ddd;
            text-decoration: none;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255, 215, 0, 0.1);
            color: var(--primary);
        }
        
        .nav-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            padding: 30px;
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
        
        .logout-btn {
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .logout-btn:hover {
            background: #FFC700;
        }
        
        /* Cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 215, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }
        
        .card h3 {
            font-size: 14px;
            color: #777;
            margin-bottom: 5px;
        }
        
        .card h2 {
            font-size: 24px;
            color: var(--dark);
        }
        
        /* Cursos Grid */
        .cursos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .curso-card {
            background: var(--light);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            position: relative;
        }
        
        .curso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        
        .curso-imagem {
            height: 160px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }
        
        .curso-content {
            padding: 20px;
        }
        
        .curso-categoria {
            display: inline-block;
            padding: 4px 8px;
            background: rgba(255, 215, 0, 0.2);
            color: var(--dark);
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .curso-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .curso-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .curso-professor {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .curso-professor strong {
            color: var(--dark);
        }
        
        .progress-container {
            margin-top: 10px;
        }
        
        .progress-bar {
            height: 8px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 4px;
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .progress-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: right;
        }
        
        .curso-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 13px;
            color: #666;
        }
        
        .curso-stat {
            display: flex;
            align-items: center;
        }
        
        .curso-stat i {
            margin-right: 5px;
            color: var(--primary);
        }
        
        .curso-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--dark);
        }
        
        .btn-primary:hover {
            background: #FFC700;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-new {
            background: #4CAF50;
            color: white;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        
        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .empty-state p {
            color: #666;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
            
            .cursos-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($aluno['nome']) ?>&background=FFD700&color=000" alt="Foto do Aluno" class="profile-img">
                <h3><?= htmlspecialchars($aluno['nome']) ?></h3>
                <p>Estudante</p>
            </div>
            
            <div class="nav-menu">
                <a href="dashboard_aluno.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Início</span>
                </a>
                <div class="nav-item active">
                    <i class="fas fa-book"></i>
                    <span>Meus Cursos</span>
                </div>
                <a href="agendaAluno.php" class="nav-item">
                    <i class="fas fa-calendar-alt"></i>
                      <span>Agenda</span>
                </a>
                <a href="configuracoes_aluno.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Configurações</span>
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-book"></i> Meus Cursos</h1>
                <button class="logout-btn" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </div>
            
            <!-- Cards Resumo -->
            <div class="cards-container">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Cursos Inscritos</h3>
                            <h2><?= $total_cursos ?></h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Aulas Concluídas</h3>
                            <h2><?= $total_aulas_concluidas ?></h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Progresso Médio</h3>
                            <h2><?= $media_progresso ?>%</h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Novos Cursos</h3>
                            <h2>Explorar</h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" onclick="window.location.href='explorar_cursos.php'">
                        <i class="fas fa-plus"></i> Descobrir
                    </button>
                </div>
            </div>
            
            <!-- Lista de Cursos -->
            <h2 style="margin-bottom: 20px;"><i class="fas fa-list"></i> Cursos em Andamento</h2>
            
            <div class="cursos-grid">
                <?php if ($total_cursos > 0): ?>
                    <?php while ($curso = mysqli_fetch_assoc($result_cursos)): ?>
                        <?php 
                        $isNew = strtotime($curso['data_inscricao']) > strtotime('-7 days');
                        $progresso = round($curso['progresso'], 1);
                        ?>
                        <div class="curso-card">
                            <?php if ($isNew): ?>
                                <span class="badge badge-new">Novo</span>
                            <?php endif; ?>
                            
                            <div class="curso-imagem">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="curso-content">
                                <span class="curso-categoria"><?= htmlspecialchars($curso['categoria']) ?></span>
                                <h3 class="curso-title"><?= htmlspecialchars($curso['nome']) ?></h3>
                                <p class="curso-desc"><?= htmlspecialchars($curso['descricao']) ?></p>
                                
                                <p class="curso-professor"><strong>Professor:</strong> <?= htmlspecialchars($curso['professor_nome']) ?></p>
                                
                                <div class="progress-container">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= $progresso ?>%"></div>
                                    </div>
                                    <div class="progress-text"><?= $progresso ?>% completo</div>
                                </div>
                                
                                <div class="curso-stats">
                                    <div class="curso-stat">
                                        <i class="fas fa-play-circle"></i>
                                        <?= $curso['aulas_concluidas'] ?>/<?= $curso['total_aulas'] ?> aulas
                                    </div>
                                    <div class="curso-stat">
                                        <i class="fas fa-calendar-alt"></i>
                                        <?= date('d/m/Y', strtotime($curso['data_inscricao'])) ?>
                                    </div>
                                </div>
                                
                                <div class="curso-actions">
                                    <button class="btn btn-secondary" onclick="window.location.href='detalhes_curso.php?id=<?= $curso['id'] ?>'">
                                        <i class="fas fa-info-circle"></i> Detalhes
                                    </button>
                                    <button class="btn btn-primary" onclick="window.location.href='aula.php?curso_id=<?= $curso['id'] ?>'">
                                        <i class="fas fa-play"></i> Continuar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h3>Você ainda não está matriculado em nenhum curso</h3>
                        <p>Explore nossa plataforma e encontre cursos que combinam com seus interesses</p>
                        <button class="btn btn-primary" onclick="window.location.href='explorar_cursos.php'">
                            <i class="fas fa-search"></i> Explorar Cursos
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Anima as barras de progresso quando a página carrega
            $('.progress-fill').each(function() {
                var width = $(this).attr('style');
                $(this).css('width', '0%');
                $(this).animate({
                    width: width
                }, 1000);
            });
        });
    </script>
</body>
</html>