




<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!-- Formatação e estilo -->
    <link rel="stylesheet" type="text/css" href="css/estilo.css">

    <title>YoungCash </title>
  
  </head>
  <body>
    <div id="home">
      <div class="container">
        <header>
          
          <nav class="navbar navbar-expand-sm navbar-light">
            <!-- Logo -->
            <a class="navbar-brand" href=""><img src="" style="width: 142px"> </a>

            <!-- Menu Hamburguer -->
            <button class="navbar-toggler" data-toggle="collapse" data-target="#target-navegacao">
              <span class="navbar-toggler-icon"></span>
            </button>

            
              <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-4"><a class="nav-link" href="cadastro.php">Cadastre-se</a></li>
                
                  <li class="nav-item mr-4"><a class="nav-link" href="planos.html">Planos</a></li>
              </ul>
              <a href="login.php"><button class="btn btn-outline-light">Entrar</button></a>

              <div class="d-flex">
                

              </div>
            </div>
          </nav>
        </header>
        
        <!-- Corpo Principal -->
        <div class="container">
          <!-- Cartão de apresentação -->
          <div class="row">
            <div class="col-md-6 align-self-center">
              <h1 class="display-4">Você Jovem!</h1>
              <p>Venha Aprender tudo sobre educação financeira, para de depender dos outros quando o assunto é dinheiro</p>
              
              <div class="input-group input-group-lg">
               
                <a href="home.php"><button type="button" class="input-group-append btn btn-primary" style="padding-top: 10px">Venha pro YoungCash</button></a>
                
              </div> 

              
            </div> <!-- fim coluna 1 -->
            <div class="col-md-6 d-none d-md-block">
              <img src="img/capa-mulher.png">
            </div>
          </div> <!-- Fim row -->
        </div> <!-- Fim corpo principal -->
      </div> <!-- fim do container total-->
    </div> <!-- fim home-->

    <section class="container"> 
      <div class="row pt-4 justify-content-center">
        
        <!-- informações 1 -->
        <article class="col-md-6 align-self-center pb-4">
          <h2>Quanto mais dinhero melhor!</h2>
          <p>Aprenda a investir da melhor forma com simulações de investimento </p>
          <button class="btn btn-primary"><a class="text-white" href="">Veja mais</a></button>
        </article> <!-- fim informações 1 -->
        
        <!-- gráfico 1 -->
        <article class="col-md-6"> 
          <img src="img/saiba.png" class="img-fluid">
        </article> <!-- fim gráfico 1 -->

        <div class="col-12 border-top border-info mb-4"></div>

        <!-- gráfico 2 --> 
        <article class="col-md-6 pb-4"> 
          <img src="img/juros.png" class="img-fluid">
        </article> <!-- fim gráfico 2 -->

        <!-- informações 2 -->
        <article class="col-md-6  align-self-center">
          <h2>Pare de gastar seu Dinheiro da forma errada!</h2>
          <p>Aprenda o melhor jeito de utilizar aquele dinheiro que sobra no final do mês</p>
          <button class="btn btn-primary"><a class="text-white" href="">Veja mais</a></button>
        </article> <!-- fim informações 2 -->

        <div class="col-12 border-top border-info mb-4"></div>
        
        <!-- informações 3 -->
        <article class="col-md-4">
          <img src="img/facil.png" class="img-fluid">
          <h3>Aprendendo de forma facil</h3>
          <p>O YoungCash vai além do básico e permite que você aprenda coisas essenciais para suas finanças. Simples como tem que ser!</p>
        </article> <!-- fim informações 3 -->

        <!-- informações 4 -->
        <article class="col-md-4">
          <img src="img/economize.png" class="img-fluid">
          <h3>Economize seu tempo</h3>
          <p>Tempo é dinheiro! quanto mais você demora pra começar mais dinheiro você perde!</p>
        </article> <!-- fim informações 4 -->

        <!-- informações 5 -->
        <article class="col-md-4">
          <img src="img/suporte.png" class="img-fluid">
          <h3>Forum de Perguntas</h3>
          <p>Consegue tirar e ajudar pessoas pelo forum de interação.</p>
        </article> <!-- fim informações 5 -->
        <div class="col-12 border-top border-info mb-4"></div>

        <div class="container">
          <h2 class="text-center text-warning mb-4">Simulador de Metas Financeiras</h2>
          <form id="finance-form" class="bg-white p-5 shadow-sm rounded">
              <div class="mb-3">
                  <label for="nome" class="form-label">Nome</label>
                  <input type="text" class="form-control" id="nome" placeholder="Insira seu nome" required>
              </div>
              <div class="mb-3">
                  <label for="meta" class="form-label">Meta Financeira (R$)</label>
                  <input type="number" class="form-control" id="meta" placeholder="Qual é sua meta financeira?" required>
              </div>
              <div class="mb-3">
                  <label for="prazo" class="form-label">Prazo (meses)</label>
                  <input type="number" class="form-control" id="prazo" placeholder="Em quantos meses?" required>
              </div>
              <button type="submit" class="btn btn-warning">Calcular</button>
          </form>
          <div id="resultado" class="mt-4"></div>
      </div>

      
        
      </div>
    </section>

    <footer class="container">
      <div class="row">  
        <nav class="navbar navbar-expand col-sm-7">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="">Recursos</a></li>
            <li class="nav-item"><a class="nav-link" href="">Benefícios</a></li>
            <li class="nav-item"><a class="nav-link" href="">Preço</a></li>
          </ul>     
        </nav>
        <div class="col-sm-5 row align-items-center justify-content-end">
            <i class="fab fa-facebook-square btn btn-outline-dark"></i>
            <i class="fab fa-twitter btn btn-outline-dark"></i>
            <i class="fab fa-instagram btn btn-outline-dark"></i>
            <i class="fab fa-youtube btn btn-outline-dark"></i>
        </div>
      </div>
    </footer>    
    





    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="script.js"></script>
  </body>
</html>
