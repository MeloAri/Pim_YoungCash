<?php
session_start();

//print_r($_SESSION);

if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true))
{
  unset($_SESSION['email']);
  unset($_SESSION['senha']);
  header('Location: login.php');
}

  $logado = $_SESSION['email'];
?>

 


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Educa</title>

   <!-- swiper css link  -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="estilo-home.css">

</head>

<body>

   <!-- header section starts  -->

   <header class="header">

      <section class="flex">

         <a href="#home" class="logo">YoungCash</a>

         

         <nav class="navbar">
            <a href="#home">Inicio</a>
            <a href="estudo.html">Aprenda</a>
            <a href="formulários.php">exercícios</a>
            <a href="simulador_invest.html">Investimento</a>
            <a href="#reviews">opniões</a>
            <a href="#contact">contato</a>
            <a href="perfil.php">Meu Perfil</a>
         </nav>

         <div id="menu-btn" class="fas fa-bars"></div>

         

      </section>

   </header>

   <!-- header section ends -->

   <!-- home section starts  -->

   <section class="home" id="home">

      <div class="row">

         <div class="content">
            <h3>Young <span>Cash</span></h3>
            <a href="#contact" class="btn">contato</a>
         </div>

         <div class="image">
            <img src="img/homg-img.svg" alt="">
         </div>

      </div>

   </section>

   <!-- home section ends -->

   <!-- couter section stars  -->

   <section class="count">

      <div class="box-container">

         <div class="box">
            <i class="fas fa-graduation-cap"></i>
            <div class="content">
               <h3>150+</h3>
               <p>cursos</p>
            </div>
         </div>

         <div class="box">
            <i class="fas fa-user-graduate"></i>
            <div class="content">
               <h3>1300+</h3>
               <p>estudantes</p>
            </div>
         </div>

         <div class="box">
            <i class="fas fa-chalkboard-user"></i>
            <div class="content">
               <h3>80+</h3>
               <p>professores</p>
            </div>
         </div>

         <div class="box">
            <i class="fas fa-face-smile"></i>
            <div class="content">
               <h3>100%</h3>
               <p>satisfação</p>
            </div>
         </div>

      </div>

   </section>

   <!-- couter section ends -->

   <!-- about section starts  -->

   <section class="about" id="about">

      <div class="row">

         <div class="image">
            <img src="img/about-img.svg" alt="">
         </div>

         <div class="content">
            <h3>Porque nos-escolher??</h3>
            <p>O YoungCash pode ser uma boa escolha para quem busca aprendizado online por ser uma plataforma com grande variedade de cursos na área financeira, desde temas básicos a especializados, e por oferecer flexibilidade de aprendizado, com cursos gratuitos e pagos.
            </p>
            <a href="#contact" class="btn">contato</a>
         </div>

      </div>

   </section>

   <!-- about section ends -->

   <!-- courses section starts  -->

   <section class="courses" id="courses">

      <h1 class="heading">Cursos <span></span></h1>

      <div class="swiper course-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <img src="img/course-1.svg" alt="">
               <h3>Educação Financeira Básica</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

            <div class="swiper-slide slide">
               <img src="img/course-2.svg" alt="">
               <h3>Investimentos Avançados</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

            <div class="swiper-slide slide">
               <img src="img/course-3.svg" alt="">
               <h3>Planejamento de Aposentadoria</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

            <div class="swiper-slide slide">
               <img src="img/course-4.svg" alt="">
               <h3>Gestão de Finanças Pessoais</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

            <div class="swiper-slide slide">
               <img src="img/course-5.svg" alt="">
               <h3>Introdução ao Mercado de Ações</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

            <div class="swiper-slide slide">
               <img src="img/course-6.svg" alt="">
               <h3>Planejamento Financeiro para a Aposentadoria</h3>
               <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Similique, repellat!</p>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- courses section ends -->

   <!-- teachers section starts  -->

   <section class="teachers" id="teachers">

      <h1 class="heading">Professores <span></span></h1>

      <div class="swiper teachers-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <img src="img/tutor-1.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>john</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="img/tutor-2.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>Lia</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="img/tutor-3.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>tadeu</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="img/tutor-4.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>Aline</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="img/tutor-5.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>Bruno</h3>
            </div>

            <div class="swiper-slide slide">
               <img src="img/tutor-6.png" alt="">
               <div class="share">
                  <a href="#" class="fab fa-facebook-f"></a>
                  <a href="#" class="fab fa-twitter"></a>
                  <a href="#" class="fab fa-instagram"></a>
                  <a href="#" class="fab fa-linkedin"></a>
               </div>
               <h3>Geralda</h3>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- teachers section ends -->

   <!-- reviews section starts  -->

   <section class="reviews" id="reviews">

      <h1 class="heading"> Opnião de <span>estudantes</span></h1>

      <div class="swiper reviews-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-1.png" alt="">
                  <div class="user-info">
                     <h3>Leo</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-2.png" alt="">
                  <div class="user-info">
                     <h3>Maria</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-3.png" alt="">
                  <div class="user-info">
                     <h3>João</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-4.png" alt="">
                  <div class="user-info">
                     <h3>Bia</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-5.png" alt="">
                  <div class="user-info">
                     <h3>Osvaldo</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

            <div class="swiper-slide slide">
               <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum necessitatibus atque fuga delectus
                  numquam consequatur velit autem distinctio possimus culpa!</p>
               <div class="user">
                  <img src="img/pic-6.png" alt="">
                  <div class="user-info">
                     <h3>Joaninha</h3>
                     <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                     </div>
                  </div>
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <!-- reviews section ends -->

   <!-- contact section starts  -->

   <section class="contact" id="contact">

      <h1 class="heading"><span>Passe alguns dos seus dados para reveber por email noticias do mundo financeiro.</span> </h1>

      <div class="row">

         <div class="image">
            <img src="img/contact-img.svg" alt="">
         </div>

         <form action="enviar_email.php" method="post">
    <span>nome</span>
    <input type="text" required placeholder="" maxlength="50" name="nome" class="box">
    <span>email</span>
    <input type="email" required placeholder="" maxlength="50" name="email" id="email" class="box">
    <span>telefone</span>
    <input type="number" required placeholder="" max="9999999999" min="0" name="telefone" class="box"
        onkeypress="if(this.value.length == 10) return false;">
    <span>profissão</span>
    <select name="couses" class="box" required>
        <option value="" disabled selected>Selecione a opção --</option>
        <option value="web developement">web developement</option>
        <option value="science and biology">science and biology</option>
        <option value="engineering">engineering</option>
        <option value="digital marketing">digital marketing</option>
        <option value="graphic design">graphic design</option>
        <option value="teaching">teaching</option>
        <option value="social studies">social studies</option>
        <option value="data analysis">data analysis</option>
        <option value="artificial intelligence">artificial intelligence</option>
    </select>
    <span>Selecione uma opção</span>
    <div class="radio">
        <input type="radio" name="gender" value="male" id="male">
        <label for="male">Homem</label>
        <input type="radio" name="gender" value="female" id="female">
        <label for="female">Mulher</label>
    </div>
    <input type="submit" value="Enviar" class="btn" name="send">
</form>

      </div>

   </section>

   <!-- contact section ends -->

   <!-- footer section starts  -->

   <footer class="footer">

      <section>

         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-linkedin"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-youtube"></a>
         </div>

         <div class="credit">&copy; copyright @ 2022 by <span>Bruno Luan</span> | all rights reserved!</div>

      </section>

   </footer>

   <!-- footer section ends -->















   <!-- swiper js link  -->
   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="script-home.js"></script>

</body>

</html>
