<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega o e-mail inserido no formulário
    $emailDestinatario = $_POST['email'];
    
    // Cria uma instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ariteomiran@gmail.com'; // Seu e-mail
        $mail->Password = 'udvq gavn krzl zefn'; // Senha do app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('ariteomiran@gmail.com', 'Ariel');
        $mail->addAddress('ariteomiran@gmail.com', 'Raphael'); // Endereço do destinatário
    
        // Corpo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Assunto do E-mail';
        $mail->Body    = $mail->Body = $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    line-height: 1.6;
                }
                h2 {
                    color: #0066cc;
                }
                p {
                    margin-bottom: 1em;
                }
                .news-section {
                    margin-top: 20px;
                }
                .news-item {
                    background-color: #f9f9f9;
                    border: 1px solid #ddd;
                    padding: 10px;
                    margin-bottom: 10px;
                }
                .news-title {
                    font-weight: bold;
                    color: #333;
                }
                .news-image {
                    width: 100%;
                    max-width: 600px;
                    height: auto;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <h2>Principais Notícias do Mundo Financeiro - [Data]</h2>
    
            <div class="news-section">
                <div class="news-item">
                    <p class="news-title">Mercados Globais em Alta Após Abertura Positiva de Wall Street</p>
                    <p>Os mercados globais registraram um crescimento significativo hoje, impulsionados pela forte abertura da Bolsa de Valores de Nova York. O índice Dow Jones subiu [x]% e o S&P 500 seguiu o mesmo caminho, com investidores reagindo positivamente a novos dados econômicos. O mercado de ações foi particularmente favorecido pela confiança renovada nas políticas fiscais e monetárias.</p>
                    <img src="cid:wall_street" alt="Wall Street" class="news-image">
                </div>
                
                <div class="news-item">
                    <p class="news-title">Banco Central Europeu Aumenta Taxa de Juros em Tentativa de Combater a Inflação</p>
                    <p>O Banco Central Europeu anunciou um aumento de [x]% em sua taxa de juros de referência, o maior desde [ano]. A decisão visa combater a inflação persistente na zona do euro e sinaliza uma mudança nas políticas de estímulo econômico. Economistas alertam que este movimento pode ter implicações em toda a economia global, afetando o crédito e os custos para as empresas.</p>
                    <img src="cid:european_central_bank" alt="Banco Central Europeu" class="news-image">
                </div>
                
                <div class="news-item">
                    <p class="news-title">Bitcoin e Criptomoedas: Mercado Volátil em Alta</p>
                    <p>O mercado de criptomoedas experimentou um crescimento notável nas últimas 24 horas, com o Bitcoin atingindo [valor]. Especialistas destacam que a crescente adoção institucional e o aumento do interesse de investidores estão por trás da recente valorização. No entanto, há preocupações sobre a volatilidade, que ainda representa um risco para investidores de longo prazo.</p>
                    <img src="cid:bitcoin_growth" alt="Crescimento do Bitcoin" class="news-image">
                </div>
                
                <div class="news-item">
                    <p class="news-title">Setor de Tecnologia Enfrenta Desafios, Mas Apresenta Oportunidades de Crescimento</p>
                    <p>Após um trimestre misto, empresas de tecnologia como [exemplo de empresas] enfrentam desafios relacionados à escassez de chips e à desaceleração do crescimento global. No entanto, analistas apontam que setores como inteligência artificial, computação em nuvem e segurança cibernética continuam a apresentar grandes oportunidades de crescimento, mesmo em tempos de incerteza econômica.</p>
                    <img src="cid:tech_growth" alt="Crescimento do Setor de Tecnologia" class="news-image">
                </div>
                
                <div class="news-item">
                    <p class="news-title">Aumento no Preço do Petróleo Afeta Mercados Emergentes</p>
                    <p>Os preços do petróleo subiram para [preço], gerando preocupações em mercados emergentes que dependem fortemente das importações de energia. Economistas afirmam que este aumento pode pressionar ainda mais a inflação, especialmente em países com moeda fraca. As negociações sobre políticas de energia e sustentabilidade se tornam ainda mais críticas no cenário atual.</p>
                    <img src="cid:oil_prices" alt="Aumento do Preço do Petróleo" class="news-image">
                </div>
                
                <div class="news-item">
                    <p class="news-title">Previsões de Crescimento do PIB Global: Expectativas de Recuperação Econômica</p>
                    <p>O Fundo Monetário Internacional (FMI) revisou suas previsões de crescimento global para 2024, elevando suas estimativas para [x]%. O aumento no ritmo de vacinação e as medidas fiscais em várias economias avançadas são apontados como principais fatores para essa recuperação econômica. No entanto, o FMI alerta que a recuperação pode ser desigual, com alguns países enfrentando dificuldades adicionais.</p>
                    <img src="cid:global_growth" alt="Crescimento Econômico Global" class="news-image">
                </div>
            </div>
    
            <p>Atenciosamente,</p>
            <p><strong>Ariel</strong></p>
        </body>
        </html>';
    
        // Anexando a imagem
        
    
        // Enviar o e-mail
        $mail->send();
        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
}
}