<?php
session_start();
    // Verifica se o formulário foi enviado e se os campos não estão vazios
    if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
        include_once('config.php'); // Inclui o arquivo de configuração/conexão com o banco de dados
        
        // Coleta os valores enviados do formulário
        $email = $_POST['email'];  
        $senha = $_POST['senha'];

        // Exibe os valores para testes (opcional, removido o comentário inválido)
        // echo 'Email: ' . $email . '<br>';
        // echo 'Senha: ' . $senha . '<br>';

        // Consulta SQL
        $sql = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";

        // Executa a consulta
        $result = $conexao->query($sql);

        // Verifica se houve resultados na consulta
        if (mysqli_num_rows($result) < 1) {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: home.php');
            exit();
        }
       
    } else {
        // Redireciona de volta para a página de login caso os campos estejam vazios
        header('Location: login.php');
        exit(); 
    }
?>
