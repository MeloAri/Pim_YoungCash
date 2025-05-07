<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redireciona para a página de login, caso não esteja logado
    exit();
}

$email = $_SESSION['email']; // Obtém o email da sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: gold;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Estilo do menu */
        nav {
            background-color: black;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        nav .logo {
            font-size: 1.5em;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: black;
        }

        /* Estilo principal */
        .container {
            height: 50%;
            margin: 60px auto 20px;
            padding: 50px;
            max-width: 900px;
            background: black;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid gold;
        }

        .profile-header h1 {
            font-size: 1.8em;
            margin: 0;
            color: white;
        }

        .profile-header p {
            margin: 5px 0;
            color: white;
        }

        .courses {
            display: flex;
            flex-wrap: wrap;
            gap: 10px; /* Menor espaçamento entre os cursos */
            margin-top: 10px;
        }

        .course {
            flex: 1 1 calc(33.333% - 10px); /* Três cursos por linha */
            background: #e3f2fd;
            border-radius: 8px;
            padding: 10px;
            border-left: 5px solid #42a5f5;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .course h2 {
            font-size: 1.2em;
            color: #1976d2;
            margin-bottom: 10px;
        }

        .progress-container {
            margin-top: 10px;
        }

        .progress-bar {
            background-color: #ddd;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            height: 8px; /* Altura reduzida da barra */
        }

        .progress-bar span {
            display: block;
            height: 100%;
            background-color: #42a5f5;
        }

        .progress-text {
            font-size: 0.8em; /* Texto menor */
            color: #555;
            text-align: center;
            margin-top: 5px;
        }

        .logout-btn {
            display: block;
            margin-top: 15px;
            padding: 8px 12px;
            background-color: #f44336;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #d32f2f;
        }

        @media (max-width: 768px) {
            .course {
                flex: 1 1 calc(50% - 10px); /* Dois cursos por linha em telas menores */
            }
        }

        @media (max-width: 480px) {
            .course {
                flex: 1 1 100%; /* Um curso por linha em telas muito pequenas */
            }
        }

        .recommendations {
            margin-top: 80px;
            padding: 20px;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .recommendations h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
            color: #444;
        }

        .recommendations .course-card {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-radius: 8px;
            transition: transform 0.3s;
        }

        .recommendations .course-card img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
        }

        .recommendations .course-card div {
            flex: 1;
        }

        .recommendations .course-card h3 {
            font-size: 1.2em;
            color: #1976d2;
            margin-bottom: 5px;
        }

        .recommendations .course-card p {
            margin: 0;
            color: #555;
        }

        .recommendations .course-card:hover {
            transform: translateY(-5px);
        }

        .star-rating {
            display: inline-block;
            font-size: 1.5em;
            color: gold;
        }
        .star-rating .star {
            color: lightgray; /* Cor padrão (inativa) */
        }
        .star-rating .star.filled {
            color: gold; /* Cor das estrelas preenchidas */
        }
    </style>
</head>

<body>
    <!-- Menu de Navegação -->
    <nav>
    <div class="logo">Meu Perfil</div>
    <ul>
        <li><a href="setings.php">Dashboard</a></li>
        <li><a href="setings.php">Configurações</a></li> <!-- Link corrigido -->
        <li><a href="setings.php"></a>
        <li><a href="setings.php">Sair</a></li>
    </ul>
</nav>

    <div class="container">
        <!-- Cabeçalho do Perfil -->
        <div class="profile-header">
            <img src="img/perfil.webp" alt="Foto de Perfil">
            <div>
                <h1>Perfil do Usuário</h1>
                <p>Email: <?php echo htmlspecialchars($email); ?></p>
                <p>Membro desde: Janeiro de 2023</p>
            </div>
        </div>

        <!-- Lista de Cursos -->
        <div class="courses">
            <div class="course">
                <h2>Educação Financeira Básica</h2>
                <p>Última Atividade: 20 de Novembro de 2024</p>
                <div class="progress-container">
                    <div class="progress-bar">
                        <span style="width: 100%;"></span>
                    </div>
                    <div class="progress-text">100%</div>
                </div>
            </div>

            <div class="course">
                <h2>Investimentos Avançados</h2>
                <p>Última Atividade: 15 de Novembro de 2024</p>
                <div class="progress-container">
                    <div class="progress-bar">
                        <span style="width: 75%;"></span>
                    </div>
                    <div class="progress-text">75%</div>
                </div>
            </div>

            <div class="course">
                <h2>Planejamento de Aposentadoria</h2>
                <p>Última Atividade: 10 de Novembro de 2024</p>
                <div class="progress-container">
                    <div class="progress-bar">
                        <span style="width: 50%;"></span>
                    </div>
                    <div class="progress-text">50%</div>
                </div>
            </div>
        </div>

        

        <!-- Botão de Logout -->
        <a href="login.php" class="logout-btn">Sair</a>
    </div>
    <!-- Menu de Navegação -->
    <nav>
        <div class="logo">Meu Perfil</div>
        <ul>
          
            <li><a href="setings.php">Configurações</a></li>
            <li><a href="suporte.html">Suporte</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>

    <!-- Recomendações -->
    <div class="recommendations">
        <h2>Recomendações de Cursos</h2>
        <div class="course-card">
            <div>
                <h3>Gestão de Finanças Pessoais</h3>
                <p>Aprenda a controlar seu orçamento e poupar dinheiro de forma inteligente.</p>
                <div class="star-rating">
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star">★</span> <!-- Estrela vazia -->
                </div>
            </div>
        </div>
        <div class="course-card">
            <div>
                <h3>Introdução ao Mercado de Ações</h3>
                <p>Entenda como o mercado de ações funciona e como investir com segurança.</p>
                <div class="star-rating">
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star filled">★</span> <!-- Estrela vazia -->
                    <span class="star">★</span> <!-- Estrela vazia -->
                </div>
            </div>
        </div>
        <div class="course-card">
            <div>
                <h3>Planejamento Financeiro para a Aposentadoria</h3>
                <p>Prepare-se para a aposentadoria com estratégias de investimento e economia.</p>
                <div class="star-rating">
                    <span class="star filled">★</span>
                    <span class="star filled">★</span>
                    <span class="star filled">★</span><!-- Estrela vazia -->
                    <span class="star filled">★</span> <!-- Estrela vazia -->
                    <span class="star filled">★</span> <!-- Estrela vazia -->
                </div>
            </div>
        </div>
    </div>
</body>

</html>
