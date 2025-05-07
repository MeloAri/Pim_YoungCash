
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
             background-color: gold;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
        }
       
        div{
            background-color: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 80px;
            border-radius: 15px;
            color: white;
        }

        input{
            padding: 20px;
            border: none;
            outline: none;
            font-size: 15px;
            border-radius: 15px;
            width: 86%;
            margin-top: 10px;
            
            
        }

        .inputSubmit{
            background-color: black;
            border: none;
            outline: none;
            padding: 15px;
        
            cursor: pointer;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            width: 100%;
        }

        .inputSubmit:hover{
            background-color: deepskyblue;
        }

        
    </style>
</head>
<body>
    <div class="tela-login">
        <h1>Login</h1>
        <form method="POST" action="testLogin.php">
    <input type="email" name="email" placeholder="Digite seu email" required><br>
    <input type="password" name="senha" placeholder="Digite sua senha" required>
    <input class="inputSubmit" type="submit" name="submit" value="Enviar"> 
    <a href="recuperarSenha.php" style="color: white; display: block; margin-top: 10px; text-align: center;">Esqueceu a senha?</a>
     </form>


  


    

        
    </div>
</body>
</html>

