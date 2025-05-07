<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha</title>
  <style>
    /* ============================
       MESMO CSS da sua tela de Login
       ============================ */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family:'Poppins',sans-serif;
      background:gold;
      display:flex; align-items:center; justify-content:center;
      height:100vh;
    }
    .tela-recuperar {
      background:rgba(0,0,0,0.6);
      padding:60px 40px;
      border-radius:15px;
      color:#fff;
      width:400px;
      box-shadow:0 0 20px rgba(0,0,0,0.5);
      text-align:center;
    }
    .tela-recuperar h1 { margin-bottom:30px; font-size:2rem; font-weight:600; }
    .tela-recuperar form { display:flex; flex-direction:column; }
    .tela-recuperar input[type="email"],
    .tela-recuperar input[type="password"] {
      padding:18px; margin-bottom:12px;
      border:none; border-radius:12px; font-size:1rem;
    }
    .tela-recuperar input[type="submit"] {
      padding:18px; margin-top:4px;
      background:#000; color:#fff;
      border:none; border-radius:12px;
      font-size:1rem; cursor:pointer;
      transition:background-color .3s;
    }
    .tela-recuperar input[type="submit"]:hover { background:gold; }
    .tela-recuperar a {
      display:block; margin-top:16px;
      color:#ddd; text-decoration:underline; font-size:.9rem;
    }
    .tela-recuperar a:hover { color:#fff; }

    /* OVERLAY e POP-UP */
    #overlay {
      position:fixed; top:0;left:0; width:100%;height:100%;
      background:rgba(0,0,0,0.7);
      display:none; z-index:1000;
    }
    #popup {
      position:fixed; top:50%; left:50%;
      transform:translate(-50%,-50%) scale(0);
      background:gold; padding:30px;
      border-radius:12px; text-align:center;
      z-index:1001;
      transition:transform .3s ease-out;
    }
    #popup.show { transform:translate(-50%,-50%) scale(1); }
    #popup button {
      margin-top:20px; padding:8px 16px;
      border:none; border-radius:6px;
      cursor:pointer;
    }
  </style>
</head>
<body>

  <div class="tela-recuperar">
    <h1>Recuperar Senha</h1>
    <!-- 1) Adicione id="frmRecup" -->
    <form id="frmRecup" method="POST" action="processaRecuperacao.php">
      <input type="email" name="email" placeholder="Digite seu e-mail" required>
      <input type="password" name="novaSenha" placeholder="Digite sua nova senha" required>
      <input type="submit" value="Alterar Senha">
    </form>
    <a href="login.php">Voltar ao Login</a>
  </div>

  <!-- overlay + pop-up -->
  <div id="overlay"></div>
  <div id="popup">
    <h3>Sucesso!</h3>
    <p>Sua senha foi alterada.</p>
    <button id="btnClose">OK</button>
  </div>

  <script>
    // 2) Agora o form será encontrado corretamente
    const form    = document.getElementById('frmRecup');
    const overlay = document.getElementById('overlay');
    const popup   = document.getElementById('popup');
    const btnClose= document.getElementById('btnClose');

    form.addEventListener('submit', async e => {
      e.preventDefault(); // não recarrega a página

      const data = new FormData(form);
      try {
        const resp = await fetch('processaRecuperacao.php', {
          method:'POST',
          body:data
        });
        if (!resp.ok) throw new Error('Erro na requisição');
        // 3) mostra o pop-up
        overlay.style.display = 'block';
        popup.classList.add('show');
      } catch(err) {
        alert('Falha ao alterar senha: ' + err.message);
      }
    });

    function hidePopup(){
      popup.classList.remove('show');
      overlay.style.display = 'none';
    }

    btnClose.addEventListener('click', hidePopup);
    overlay.addEventListener('click', hidePopup);
  </script>

</body>
</html>
