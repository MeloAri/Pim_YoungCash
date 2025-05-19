<?php
session_start();
// Verificar se o usuário está logado como professor
if (!isset($_SESSION['tipo_usuario'])) {  // Parêntese adicionado aqui
    header("Location: login.php");
    exit();
}

if ($_SESSION['tipo_usuario'] != 'professor') {
    header("Location: acesso_negado.php");
    exit();
}

include_once('config.php');

// Buscar dados do professor
$professor_id = $_SESSION['usuario_id'];
$query_professor = "SELECT * FROM usuarios WHERE id = $professor_id";
$result_professor = mysqli_query($conexao, $query_professor);
$professor = mysqli_fetch_assoc($result_professor);

// Buscar alunos cadastrados
$query_alunos = "SELECT * FROM usuarios WHERE tipo = 'estudante' ORDER BY nome ASC";
$result_alunos = mysqli_query($conexao, $query_alunos);

// Buscar cursos dos alunos (exemplo simplificado)
$query_cursos = "SELECT c.nome as curso_nome, u.nome as aluno_nome, u.id as aluno_id 
                 FROM cursos c
                 JOIN aluno_cursos ac ON c.id = ac.curso_id
                 JOIN usuarios u ON ac.aluno_id = u.id
                 ORDER BY u.nome ASC";
$result_cursos = mysqli_query($conexao, $query_cursos);

// Organizar cursos por aluno
$cursos_por_aluno = [];
while ($row = mysqli_fetch_assoc($result_cursos)) {
    $cursos_por_aluno[$row['aluno_id']][] = $row['curso_nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Professor | YoungCash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
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
        }
        
        .logout-btn {
            background: var(--primary);
            color: var(--dark);
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
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
        
        /* Tables */
        .table-container {
            background: var(--light);
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: var(--gray);
            font-weight: 600;
        }
        
        tr:hover {
            background: rgba(255, 215, 0, 0.05);
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-primary {
            background: rgba(255, 215, 0, 0.2);
            color: var(--dark);
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
        }
        
        .action-btn.view {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }
        
        .action-btn.edit {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        @media (max-width: 768px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                display: none;
            }
        }

        a{
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($professor['nome']) ?>&background=FFD700&color=000" alt="Foto do Professor" class="profile-img">
                <h3><?= htmlspecialchars($professor['nome']) ?></h3>
                <p>Professor</p>
            </div>
            
            <div class="nav-menu">
                <div class="nav-item active">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Monitoramento</span>
                </div>
                <div class="nav-item">
                    <i class="fas fa-book"></i>
                    <a href="cursosProfessor.php"><span>Meus Cursos</span></a>
                </div>
                <div class="nav-item">
                    <i class="fas fa-calendar-alt"></i>
                    <a href="calendarioProfessor.php"><span>Agenda</span></a>
                </div>
                <div class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Configurações</span>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1><i class="fas fa-chalkboard-teacher"></i> Monitoramento de Alunos</h1>
                <button class="logout-btn" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </div>
            
            <!-- Cards Resumo -->
            <div class="cards-container">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Total de Alunos</h3>
                            <h2><?= mysqli_num_rows($result_alunos) ?></h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Cursos Ativos</h3>
                            <h2>15</h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Atividades Pendentes</h3>
                            <h2>8</h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3>Média de Progresso</h3>
                            <h2>78%</h2>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabela de Alunos -->
            <div class="table-container">
                <h2><i class="fas fa-user-graduate"></i> Lista de Alunos</h2>
                <p>Visualize todos os alunos cadastrados na plataforma e seus cursos</p>
                
                <table id="tabelaAlunos">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Cursos</th>
                            <th>Progresso</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($aluno = mysqli_fetch_assoc($result_alunos)): ?>
                        <tr>
                            <td><?= htmlspecialchars($aluno['nome']) ?></td>
                            <td><?= htmlspecialchars($aluno['email']) ?></td>
                            <td><?= htmlspecialchars($aluno['telefone']) ?></td>
                            <td>
    <?php if (isset($cursos_por_aluno[$aluno['id']])) { ?>
        <?php foreach ($cursos_por_aluno[$aluno['id']] as $curso): ?>
            <span class="badge badge-primary"><?= htmlspecialchars($curso) ?></span>
        <?php endforeach; ?>
    <?php } else { ?>
        <span>Nenhum curso</span>
    <?php } ?>
</td>
                            <td>
                                <div style="background: #eee; border-radius: 10px; height: 10px; width: 100%;">
                                    <div style="background: var(--primary); width: <?= rand(30, 100) ?>%; height: 100%; border-radius: 10px;"></div>
                                </div>
                                <small><?= rand(30, 100) ?>% completo</small>
                            </td>
                            <td>
                                <button class="action-btn view" onclick="verAluno(<?= $aluno['id'] ?>)">
                                    <i class="fas fa-eye"></i> Ver
                                </button>
                                <button class="action-btn edit" onclick="editarAluno(<?= $aluno['id'] ?>)">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaAlunos').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
                },
                responsive: true
            });
        });
        
        function verAluno(id) {
            window.location.href = 'detalhes_aluno.php?id=' + id;
        }
        
        function editarAluno(id) {
            window.location.href = 'editar_aluno.php?id=' + id;
        }
    </script>
</body>
</html>