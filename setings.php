<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redireciona para a página de login, caso não esteja logado
    exit();
}

$email = $_SESSION['email']; // Obtém o email da sessão

// Conexão com o banco de dados
$host = "localhost";
$dbname = "projeto_pim";
$username = "root";
$password = "Siena5173";
$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

// Atualização de configurações
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'] ?? $email;
    $new_password = $_POST['password'] ?? '';  // Novo valor da senha
    $new_theme = $_POST['theme'] ?? 'light';

    try {
        // Depuração: Exibir valores recebidos
        echo "<pre>";
        echo "Novo email: $new_email\n";
        echo "Nova senha: " . ($new_password ? $new_password : 'Sem senha') . "\n";
        echo "Novo tema: $new_theme\n";
        echo "</pre>";

        // Se a senha foi alterada, criptografa a nova senha
        if (!empty($new_password)) {
            $new_password = password_hash($new_password, PASSWORD_DEFAULT); // Criptografando a senha

            // Depuração: Exibir a senha criptografada
            echo "<pre>";
            echo "Senha criptografada: $new_password\n";
            echo "</pre>";

            // Atualizando email e senha no banco de dados
            $stmt = $conn->prepare("UPDATE user_settings SET email = ?, password = ?, theme = ? WHERE email = ?");
            $stmt->execute([$new_email, $new_password, $new_theme, $email]);

            // Depuração: Verificar quantas linhas foram afetadas pela consulta
            echo "<pre>";
            echo "Linhas afetadas: " . $stmt->rowCount() . "\n";
            echo "</pre>";

        } else {
            // Se a senha não foi alterada, apenas atualiza o email e tema
            $stmt = $conn->prepare("UPDATE user_settings SET email = ?, theme = ? WHERE email = ?");
            $stmt->execute([$new_email, $new_theme, $email]);

            // Depuração: Verificar quantas linhas foram afetadas pela consulta
            echo "<pre>";
            echo "Linhas afetadas (sem senha): " . $stmt->rowCount() . "\n";
            echo "</pre>";
        }

        // Atualiza a sessão com o novo email e tema
        $_SESSION['email'] = $new_email;
        $_SESSION['theme'] = $new_theme;

        // Feedback para o usuário
        echo "<script>alert('Configurações atualizadas com sucesso!');</script>";
        header("Location: setings.php"); // Redireciona para a página de configurações

    } catch (PDOException $e) {
        // Exibe erro caso aconteça um problema na consulta
        echo "Erro ao atualizar as configurações: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para o tema claro */
        body.light {
            background-color: #f4f4f9;
            color: #333;
        }
        /* Estilos para o tema escuro */
        body.dark {
            background-color: #333;
            color: #f4f4f9;
        }

        /* Estilos gerais para a página */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
        }

        /* Barra de navegação */
        nav {
            background-color: #42a5f5;
            padding: 15px 0;
            text-align: center;
        }

        nav ul {
            list-style: none;
        }

        nav ul li {
            display: inline-block;
            margin: 0 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Estilo do título */
        h2 {
            text-align: center;
            margin: 30px 0;
            font-size: 28px;
        }

        /* Formulário */
        form {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input[type="text"], form input[type="password"], form select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus, form input[type="password"]:focus, form select:focus {
            border-color: #42a5f5;
            outline: none;
        }

        /* Botão */
        button {
            background-color: #42a5f5;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1976d2;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            h2 {
                font-size: 24px;
            }
        }

    </style>
</head>
<body class="<?php echo isset($_SESSION['theme']) ? $_SESSION['theme'] : 'light'; ?>">

<!-- Barra de navegação -->
<nav>
    <ul>
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="login.php">Sair</a></li>
    </ul>
</nav>

<!-- Título -->
<h2>Configurações de Conta</h2>

<!-- Formulário de Configurações -->
<form method="POST">
    <label for="email">Novo Email:</label>
    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label for="password">Nova Senha:</label>
    <input type="password" id="password" name="password" placeholder="Digite uma nova senha">

    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>
