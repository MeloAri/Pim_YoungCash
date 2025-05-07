<?php
$servername = "localhost";
$username = "root"; 
$password = "Siena5173";     
$database = "projeto_pim"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$email = $_POST['email'];
$novaSenha = $_POST['novaSenha'];

// Atualizar a senha
$sql = "UPDATE usuarios SET senha = '$novaSenha' WHERE email = '$email'";

if ($conn->query($sql) === TRUE) {
    // Redireciona para recuperarSenha.php com sucesso
    header("Location: recuperarSenha.php?sucesso=1");
    exit();
} else {
    // Redireciona com erro
    header("Location: recuperarSenha.php?erro=1");
    exit();
}

$conn->close();
?>
