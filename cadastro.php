<?php
if (isset($_POST['submit'])) {
    // Exibe todos os dados enviados via POST
    // print_r($_POST);

    include_once('config.php');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $data_nasc = $_POST['data_nascimento'];
    $senha = $_POST['senha'];

    // Inserindo os dados no banco de dados, agora com as variáveis corretamente entre aspas
    $result = mysqli_query($conexao, "INSERT INTO usuarios(nome, email, telefone, data_nasc, senha) VALUES ('$nome', '$email', '$telefone', '$data_nasc', '$senha')");

    if ($result) {
        echo "Dados inseridos com sucesso!";
    } else {
        echo "Erro ao inserir os dados: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-image: linear-gradient(to right, rgb(235, 221, 98), rgb(245, 179, 57));
        }

        .box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 15px;
            width: 20%;
        }

        fieldset {
            border: 3px solid gold;
            color: white;
        }

        legend {
            color: white;
            border: 1px solid gold;
            padding: 10px;
            text-align: center;
            background-color: gold;
            border-radius: 8px;
        }

        .inputBox {
            position: relative;
        }

        .inputUser {
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: white;
            font-size: 15px;
            width: 100%;
            letter-spacing: 2px;
        }

        .labelInput {
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
            color: white;
        }

        .inputUser:focus ~ .labelInput {
            top: -20px;
            font-size: 14px;
            color: white;
        }

        #data_nascimento {
            border: none;
            padding: 8px;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        #submit {
            background-image: linear-gradient(to right, rgb(245, 179, 57), rgb(235, 221, 98));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            margin-top: 30px;
        }

        .botao-login{
            background-image: linear-gradient(to right, rgb(245, 179, 57), rgb(235, 221, 98));
            width: 100%;
            border: none;
            padding: 15px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            border-radius: 10px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="box">
        <form action="cadastro.php" method="POST">
            <fieldset>
                <legend><b>Cadastre-se</b></legend>
                <br>
                <div class="inputBox">
                    <label for="nome" class="labelInput">Nome Completo</label>
                    <input type="text" name="nome" id="nome" class="inputUser" required>
                </div>
                <br>
                <div class="inputBox">
                    <label for="email" class="labelInput">Email</label>
                    <input type="text" name="email" id="email" class="inputUser" required> <!-- Corrigido aqui -->
                </div>
                
                <br>
                <div class="inputBox">
                    <label for="telefone" class="labelInput">Telefone</label>
                    <input type="tel" name="telefone" id="telefone" class="inputUser" required>
                </div>
                <br>
                <label for="data_nascimento"><b>Data de Nascimento</b></label>
                <input type="date" name="data_nascimento" id="data_nascimento" required>
                <br>
                <br>
                <div class="inputBox">
                    <label for="nome" class="labelInput">Senha</label>
                    <input type="password" name="senha" id="senha" class="inputUser" required>
                </div>
                <br>
               <input type="submit" name="submit" id="submit">
            </fieldset>
        </form>

        <!-- Botão de Redirecionamento para Login -->
        <form action="login.php">
            <button type="submit" class="botao-login">Faça Login</button>
        </form>
    </div>
</body>
</html>
