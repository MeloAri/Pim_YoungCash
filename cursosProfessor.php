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

// Processar formulário de criação de curso
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $professor_id = $_SESSION['usuario_id'];
    
    $query = "INSERT INTO cursos (nome, descricao, professor_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nome, $descricao, $professor_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: meus_cursos.php?sucesso=1");
        exit();
    } else {
        $erro = "Erro ao criar curso: " . mysqli_error($conexao);
    }
}

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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Curso | YoungCash</title>
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
        
        /* Sidebar Estilo Moderno */
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
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255, 215, 0, 0.1);
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
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: var(--dark);
            border: none;
        }
        
        .btn-primary:hover {
            background: #FFC700;
        }
        
        /* Formulário */
        .form-container {
            max-width: 700px;
            margin: 0 auto;
            background: var(--light);
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-danger {
            background: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF9A9A;
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
            color: #FFF;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Estilo Moderno -->
        <div class="sidebar">
            <div class="profile">
                <div class="avatar"><?= $iniciais ?></div>
                <div class="profile-name"><?= htmlspecialchars($professor['nome']) ?></div>
                <div class="profile-role">Professor</div>
            </div>
            
            <div class="nav-menu">
                <div class="nav-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <a href="homeProfessor.php"><span>Monitoramento</span></a>
                </div>
                <div class="nav-item active">
                    <i class="fas fa-book"></i>
                    <span>Meus Cursos</span>
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
                <h1><i class="fas fa-plus-circle"></i> Criar Novo Curso</h1>
                <a href="meus_cursos.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            
            <div class="form-container">
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> <?= $erro ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nome">Nome do Curso</label>
                        <input type="text" id="nome" name="nome" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição do Curso</label>
                        <textarea id="descricao" name="descricao" class="form-control" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="padding: 12px 25px;">
                        <i class="fas fa-save"></i> Criar Curso
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>