<?php
// Inicialização de variáveis
$tipo_usuario = '';
$nome = $email = $telefone = $data_nasc = $senha = '';
$erro = '';
$sucesso = '';
$certificado_destino = NULL;

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    include_once('config.php');
    
    // Coletar dados do formulário
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nasc = $_POST['data_nascimento'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    // Processar upload do certificado (apenas para professores)
    if ($tipo_usuario == 'professor') {
        // Verificar se arquivo foi enviado
        if (!isset($_FILES['certificado']) || $_FILES['certificado']['error'] != UPLOAD_ERR_OK) {
            $erro = "<div class='alert error'>Erro no envio do certificado. Código: " . $_FILES['certificado']['error'] . "</div>";
        } else {
            $certificado_nome = $_FILES['certificado']['name'];
            $certificado_tmp = $_FILES['certificado']['tmp_name'];
            
            // Caminho absoluto para a pasta de certificados
            $caminho_certificados = 'C:/Users/arite/OneDrive/Área de Trabalho/certificados/';
            
            // Verificar se o diretório existe e é gravável
            if (!is_dir($caminho_certificados)) {
                $erro = "<div class='alert error'>Diretório de certificados não encontrado: $caminho_certificados</div>";
            } elseif (!is_writable($caminho_certificados)) {
                $erro = "<div class='alert error'>Diretório de certificados não tem permissão de escrita</div>";
            } else {
                // Verificar extensão
                $extensoes_permitidas = ['pdf', 'jpg', 'jpeg', 'png'];
                $extensao = strtolower(pathinfo($certificado_nome, PATHINFO_EXTENSION));
                
                if (!in_array($extensao, $extensoes_permitidas)) {
                    $erro = "<div class='alert error'>Tipo de arquivo não permitido. Apenas PDF, JPG, JPEG ou PNG.</div>";
                } else {
                    // Verificar tamanho (5MB máximo)
                    $tamanho_maximo = 5242880;
                    if ($_FILES['certificado']['size'] > $tamanho_maximo) {
                        $erro = "<div class='alert error'>O arquivo é muito grande. Tamanho máximo permitido: 5MB.</div>";
                    } else {
                        // Gerar nome único para o arquivo
                        $certificado_nome_unico = uniqid() . '_' . basename($certificado_nome);
                        $certificado_destino = $caminho_certificados . $certificado_nome_unico;
                        
                        // Mover arquivo
                        if (!move_uploaded_file($certificado_tmp, $certificado_destino)) {
                            $erro = "<div class='alert error'>Falha ao salvar o certificado. Erro: " . error_get_last()['message'] . "</div>";
                        } else {
                            $certificado_destino = 'certificados/' . $certificado_nome_unico; // Caminho relativo para o banco de dados
                        }
                    }
                }
            }
        }
    }

    // Se não houve erros, inserir no banco de dados
    if (empty($erro)) {
        $result = mysqli_query($conexao, "INSERT INTO usuarios(tipo, nome, email, telefone, data_nasc, senha, certificado) 
                                        VALUES ('$tipo_usuario', '$nome', '$email', '$telefone', '$data_nasc', '$senha', '$certificado_destino')");

        if ($result) {
            $sucesso = "<div class='alert success'>Cadastro realizado com sucesso!</div>";
            // Limpar campos após sucesso
            $tipo_usuario = $nome = $email = $telefone = $data_nasc = $senha = '';
        } else {
            $erro = "<div class='alert error'>Erro ao cadastrar: " . mysqli_error($conexao) . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | YoungCash</title>
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
        
        .container {
            width: 100%;
            max-width: 500px;
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
        
        .header {
            background: var(--dark);
            color: var(--primary);
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .user-type {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .user-type label {
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
            color: var(--dark);
            transition: all 0.3s;
        }
        
        .user-type input[type="radio"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid var(--primary);
            border-radius: 50%;
            margin-right: 8px;
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
            padding: 15px 15px 15px 45px;
            border: 2px solid var(--gray);
            border-radius: 10px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s;
            background: var(--gray);
        }
        
        .input-group input:focus {
            border-color: var(--primary);
            background: var(--light);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark);
            opacity: 0.6;
        }
        
        .input-group input:focus + i {
            color: var(--primary);
            opacity: 1;
        }
        
        .input-group label {
            position: absolute;
            left: 45px;
            top: 15px;
            color: var(--dark);
            opacity: 0.6;
            font-size: 16px;
            transition: all 0.3s;
            pointer-events: none;
        }
        
        .input-group label.active {
            top: -10px;
            left: 45px;
            font-size: 12px;
            background: var(--light);
            padding: 0 5px;
            color: var(--primary);
            opacity: 1;
        }
        
        #data_nascimento {
            padding-left: 45px;
        }
        
        .certificado-field {
            display: none;
            margin-top: 20px;
            background: var(--gray);
            padding: 15px;
            border-radius: 10px;
            border: 2px dashed rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .certificado-field.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        .certificado-field label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
        }
        
        .file-input {
            position: relative;
            display: inline-block;
        }
        
        .file-input input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label {
            display: inline-block;
            padding: 10px 20px;
            background: var(--primary);
            color: var(--dark);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-input-label:hover {
            background: #FFC700;
        }
        
        .file-name {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: var(--dark);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: var(--dark);
        }
        
        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        .alert.success {
            background: rgba(46, 204, 113, 0.2);
            color: #2ECC71;
            border: 1px solid #2ECC71;
        }
        
        .alert.error {
            background: rgba(231, 76, 60, 0.2);
            color: #E74C3C;
            border: 1px solid #E74C3C;
        }
        
        @media (max-width: 576px) {
            .container {
                border-radius: 15px;
            }
            
            .header {
                padding: 20px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .user-type {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user-plus"></i> Criar Conta</h1>
            <p>Junte-se à nossa comunidade educacional</p>
        </div>
        
        <div class="form-container">
            <?php 
            // Exibir mensagens de erro/sucesso
            echo $erro;
            echo $sucesso;
            ?>
            
            <form action="cadastro.php" method="POST" enctype="multipart/form-data">
                <div class="user-type">
                    <label>
                        <input type="radio" name="tipo_usuario" value="estudante" <?= ($tipo_usuario == '' || $tipo_usuario == 'estudante') ? 'checked' : '' ?>> 
                        <i class="fas fa-graduation-cap"></i> Estudante
                    </label>
                    <label>
                        <input type="radio" name="tipo_usuario" value="professor" <?= $tipo_usuario == 'professor' ? 'checked' : '' ?>> 
                        <i class="fas fa-chalkboard-teacher"></i> Professor
                    </label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nome" id="nome" class="inputUser" required value="<?= htmlspecialchars($nome) ?>">
                    <label for="nome" class="<?= !empty($nome) ? 'active' : '' ?>">Nome Completo</label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" class="inputUser" required value="<?= htmlspecialchars($email) ?>">
                    <label for="email" class="<?= !empty($email) ? 'active' : '' ?>">Email</label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="tel" name="telefone" id="telefone" class="inputUser" required value="<?= htmlspecialchars($telefone) ?>">
                    <label for="telefone" class="<?= !empty($telefone) ? 'active' : '' ?>">Telefone</label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-birthday-cake"></i>
                    <input type="date" name="data_nascimento" id="data_nascimento" class="inputUser" required value="<?= htmlspecialchars($data_nasc) ?>">
                    <label for="data_nascimento" class="<?= !empty($data_nasc) ? 'active' : '' ?>"></label>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="senha" id="senha" class="inputUser" required>
                    <label for="senha">Senha</label>
                </div>
                
                <div id="certificadoField" class="certificado-field <?= $tipo_usuario == 'professor' ? 'active' : '' ?>">
                    <label for="certificado"><i class="fas fa-file-certificate"></i> Comprovação de Qualificação</label>
                    <div class="file-input">
                        <input type="file" name="certificado" id="certificado" accept=".pdf,.jpg,.jpeg,.png" <?= $tipo_usuario == 'professor' ? 'required' : '' ?>>
                        <label for="certificado" class="file-input-label">Selecionar Arquivo</label>
                        <span id="fileName" class="file-name">Nenhum arquivo selecionado</span>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn">
                    <i class="fas fa-user-plus"></i> Cadastrar
                </button>
            </form>
            
            <div class="login-link">
                Já tem uma conta? <a href="login.php">Faça login aqui</a>
            </div>
        </div>
    </div>

    <script>
        // Mostrar campo de certificado apenas para professores
        document.querySelectorAll('input[name="tipo_usuario"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const certificadoField = document.getElementById('certificadoField');
                if (this.value === 'professor') {
                    certificadoField.classList.add('active');
                    document.getElementById('certificado').setAttribute('required', '');
                } else {
                    certificadoField.classList.remove('active');
                    document.getElementById('certificado').removeAttribute('required');
                }
            });
        });
        
        // Mostrar nome do arquivo selecionado
        document.getElementById('certificado').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : 'Nenhum arquivo selecionado';
            document.getElementById('fileName').textContent = fileName;
        });
        
        // Animação suave ao carregar
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.container').style.opacity = '1';
            
            // Ativar labels para campos preenchidos
            document.querySelectorAll('.input-group input').forEach(input => {
                const label = input.parentElement.querySelector('label');
                if (input.value) {
                    label.classList.add('active');
                }
            });
        });
        
        // Atualização para lidar com as labels
        document.querySelectorAll('.input-group input').forEach(input => {
            const label = input.parentElement.querySelector('label');
            
            // Evento de digitação
            input.addEventListener('input', function() {
                if (this.value) {
                    label.classList.add('active');
                } else {
                    label.classList.remove('active');
                }
            });
            
            // Evento ao focar
            input.addEventListener('focus', function() {
                label.classList.add('active');
            });
            
            // Evento ao perder o foco
            input.addEventListener('blur', function() {
                if (!this.value) {
                    label.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>