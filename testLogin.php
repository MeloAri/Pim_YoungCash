<?php
session_start();
include_once('config.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Verificar credenciais no banco de dados
    $query = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha' AND tipo = '$tipo_usuario'";
    $result = mysqli_query($conexao, $query);

    if (mysqli_num_rows($result) == 1) {
        $usuario = mysqli_fetch_assoc($result);
        
        // Armazenar dados na sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['tipo_usuario'] = $usuario['tipo'];
        
        // Redirecionar conforme o tipo de usuário
        if ($tipo_usuario == 'professor') {
            header("Location: homeProfessor.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        // Login falhou
        header("Location: login.php?erro=1");
        exit();
    }
} else {
    // Acesso direto ao arquivo
    header("Location: login.php");
    exit();
}
?>