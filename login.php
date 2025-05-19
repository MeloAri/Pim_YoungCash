<?php
session_start();
$erro = isset($_GET['erro']) ? $_GET['erro'] : 0;
include_once('config.php');

// Verifica se é uma submissão de formulário
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Consulta segura usando prepared statements
    $query = "SELECT * FROM usuarios WHERE email = ? AND tipo = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $tipo_usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $usuario = mysqli_fetch_assoc($result);
        
        // Verifica a senha (DEVE usar password_verify se as senhas estiverem com hash)
        if ($senha === $usuario['senha']) { // REMOVA ESTA LINHA SE USAR HASH
        // if (password_verify($senha, $usuario['senha'])) { // USE ESTA LINHA PARA HASH
            
            // Armazena dados na sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];
            
            // Redireciona conforme o tipo de usuário
            header("Location: " . ($tipo_usuario == 'professor' ? 'homeProfessor.php' : 'home.php'));
            exit();
        }
    }
    
    // Login falhou
    header("Location: login.php?erro=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | YoungCash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #FFD700;
            --secondary: #FFA500;
            --dark: #1A1A1A;
            --light: #FFFFFF;
            --gray: #F0F0F0;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header {
            background: var(--dark);
            color: var(--primary);
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            font-size: 16px;
            opacity: 0.8;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .user-type {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .user-type label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            color: var(--dark);
            transition: all 0.3s;
            padding: 10px 15px;
            border-radius: 8px;
            background: rgba(255, 215, 0, 0.1);
        }
        
        .user-type label:hover {
            background: rgba(255, 215, 0, 0.2);
        }
        
        .user-type input[type="radio"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type input[type="radio"]:checked {
            background: var(--primary);
        }
        
        .user-type input[type="radio"]:checked::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--dark);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid var(--gray);
            border-radius: 12px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
            background: var(--gray);
        }
        
        .input-group input:focus {
            border-color: var(--primary);
            background: var(--light);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark);
            opacity: 0.6;
            font-size: 18px;
        }
        
        .input-group input:focus + i {
            color: var(--primary);
            opacity: 1;
        }
        
        .btn-login {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--dark);
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }
        
        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .login-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 15px;
        }
        
        .login-link:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        .login-link i {
            font-size: 14px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #777;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .divider::before {
            margin-right: 15px;
        }
        
        .divider::after {
            margin-left: 15px;
        }
        
        .btn-register {
            display: flex;
    align-items: center;
    justify-content: center;
    /* REMOVI O ".input" SOLTO QUE CAUSAVA ERRO */
    gap: 10px;
}

        
        
        .btn-register:hover {
            background: rgba(255, 215, 0, 0.1);
        }
        
        @media (max-width: 576px) {
            .login-container {
                border-radius: 15px;
            }
            
            .login-header {
                padding: 25px;
            }
            
            .login-body {
                padding: 25px;
            }
            
            .user-type {
                flex-direction: column;
                gap: 10px;
            }
            
            .login-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-sign-in-alt"></i> Acessar Conta</h1>
            <p>Entre para continuar sua jornada educacional</p>
        </div>
        
        <div class="login-body">
        <form method="POST" action="testLogin.php">
                <div class="user-type">
                    <label>
                        <input type="radio" name="tipo_usuario" value="estudante" checked> 
                        <i class="fas fa-graduation-cap"></i> Estudante
                    </label>
                    <label>
                        <input type="radio" name="tipo_usuario" value="professor"> 
                        <i class="fas fa-chalkboard-teacher"></i> Professor
                    </label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="senha" placeholder="Senha" required>
                </div>
                
                <button type="submit" name="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
                
                <div class="login-links">
                    <a href="recuperarSenha.php" class="login-link">
                        <i class="fas fa-key"></i> Esqueceu a senha?
                    </a>
                    <a href="#" class="login-link">
                        <i class="fas fa-question-circle"></i> Ajuda
                    </a>
                </div>
                
                <div class="divider">ou</div>
                
                <a href="cadastro.php" class="btn-register">
                    <i class="fas fa-user-plus"></i> Criar nova conta
                </a>

                <?php if ($erro == 1): ?>
    <div style="background: #FFEBEE; color: #C62828; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #EF9A9A;">
        <i class="fas fa-exclamation-circle"></i> Email ou senha incorretos!
    </div>
<?php endif; ?>
            </form>
        </div>
    </div>

    <script>
        // Animação suave ao carregar
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.login-container').style.opacity = '1';
        });
    </script>
</body>
</html>