<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Senha | YoungCash</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #FFD700;
      --secondary: #FFA500;
      --dark: #1A1A1A;
      --light: #FFFFFF;
      --gray: #F0F0F0;
      --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      --error: #E74C3C;
      --success: #2ECC71;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', 'Segoe UI', sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    
    .recovery-container {
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
    
    .recovery-header {
      background: var(--dark);
      color: var(--primary);
      padding: 30px;
      text-align: center;
      position: relative;
    }
    
    .recovery-header h1 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 10px;
    }
    
    .recovery-header p {
      font-size: 16px;
      opacity: 0.8;
    }
    
    .recovery-body {
      padding: 30px;
    }
    
    .input-group {
      position: relative;
      margin-bottom: 25px;
    }
    
    .input-group input {
      width: 100%;
      padding: 16px 16px 16px 50px;
      border: 2px solid var(--gray);
      border-radius: 12px;
      font-size: 16px;
      outline: none;
      transition: all 0.3s;
      background: var(--gray);
    }
    
    .input-group input:focus {
      border-color: var(--primary);
      background: var(--light);
      box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
    }
    
    .input-group i {
      position: absolute;
      left: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--dark);
      opacity: 0.6;
      font-size: 18px;
    }
    
    .input-group input:focus + i {
      color: var(--primary);
      opacity: 1;
    }
    
    .btn-recovery {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(to right, var(--primary), var(--secondary));
      color: var(--dark);
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      margin-top: 10px;
      box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }
    
    .btn-recovery:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
    }
    
    .back-link {
      display: block;
      text-align: center;
      margin-top: 25px;
      color: var(--dark);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    
    .back-link:hover {
      color: var(--secondary);
      text-decoration: underline;
    }
    
    /* Popup de Sucesso */
    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: none;
      z-index: 1000;
      backdrop-filter: blur(5px);
    }
    
    #popup {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0);
      background: var(--light);
      padding: 40px;
      border-radius: 20px;
      text-align: center;
      z-index: 1001;
      transition: transform 0.3s ease-out;
      box-shadow: var(--shadow);
      width: 90%;
      max-width: 400px;
      border: 4px solid var(--success);
    }
    
    #popup.show {
      transform: translate(-50%, -50%) scale(1);
      animation: pulse 0.5s ease-in-out;
    }
    
    @keyframes pulse {
      0% { transform: translate(-50%, -50%) scale(1); }
      50% { transform: translate(-50%, -50%) scale(1.05); }
      100% { transform: translate(-50%, -50%) scale(1); }
    }
    
    #popup h3 {
      color: var(--success);
      font-size: 24px;
      margin-bottom: 15px;
    }
    
    #popup p {
      color: var(--dark);
      font-size: 16px;
      margin-bottom: 25px;
    }
    
    #popup button {
      padding: 12px 24px;
      background: var(--success);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    #popup button:hover {
      background: #27ae60;
      transform: translateY(-2px);
    }
    
    @media (max-width: 576px) {
      .recovery-container {
        border-radius: 15px;
      }
      
      .recovery-header {
        padding: 25px;
      }
      
      .recovery-body {
        padding: 25px;
      }
      
      .input-group input {
        padding: 14px 14px 14px 45px;
      }
    }
  </style>
</head>
<body>

  <div class="recovery-container">
    <div class="recovery-header">
      <h1><i class="fas fa-key"></i> Recuperar Senha</h1>
      <p>Redefina sua senha para acessar sua conta</p>
    </div>
    
    <div class="recovery-body">
      <form id="frmRecup" method="POST" action="processaRecuperacao.php">
        <div class="input-group">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" placeholder="Digite seu e-mail" required>
        </div>
        
        <div class="input-group">
          <i class="fas fa-lock"></i>
          <input type="password" name="novaSenha" placeholder="Digite sua nova senha" required>
        </div>
        
        <button type="submit" class="btn-recovery">
          <i class="fas fa-sync-alt"></i> Redefinir Senha
        </button>
      </form>
      
      <a href="login.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Voltar ao Login
      </a>
    </div>
  </div>

  <!-- Popup de Sucesso -->
  <div id="overlay"></div>
  <div id="popup">
    <h3><i class="fas fa-check-circle"></i> Sucesso!</h3>
    <p>Sua senha foi alterada com sucesso.</p>
    <button id="btnClose">Continuar</button>
  </div>

  <script>
    const form = document.getElementById('frmRecup');
    const overlay = document.getElementById('overlay');
    const popup = document.getElementById('popup');
    const btnClose = document.getElementById('btnClose');

    form.addEventListener('submit', async e => {
      e.preventDefault();

      const data = new FormData(form);
      try {
        // Simulação de requisição (substitua pelo seu código real)
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mostrar popup de sucesso
        overlay.style.display = 'block';
        popup.classList.add('show');
        
        // Resetar formulário
        form.reset();
      } catch(err) {
        alert('Falha ao alterar senha: ' + err.message);
      }
    });

    function hidePopup() {
      popup.classList.remove('show');
      overlay.style.display = 'none';
      window.location.href = 'login.php'; // Redireciona após fechar o popup
    }

    btnClose.addEventListener('click', hidePopup);
    overlay.addEventListener('click', hidePopup);

    // Animação ao carregar
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelector('.recovery-container').style.opacity = '1';
    });
  </script>

</body>
</html>