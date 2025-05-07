// Simulador de Metas Financeiras
document.getElementById('finance-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const nome = document.getElementById('nome').value;
    const meta = parseFloat(document.getElementById('meta').value);
    const prazo = parseInt(document.getElementById('prazo').value);
    const resultado = document.getElementById('resultado');

    if (!isNaN(meta) && !isNaN(prazo) && meta > 0 && prazo > 0) {
        const valorMensal = (meta / prazo).toFixed(2);
        resultado.innerHTML = `${nome}, você precisará economizar <strong>R$ ${valorMensal}</strong> por mês para atingir sua meta em <strong>${prazo}</strong> meses.`;
    } else {
        resultado.innerHTML = '<p class="text-danger">Por favor, insira valores válidos.</p>';
    }
});

// Gráfico da Bolsa de Valores usando Chart.js
const ctx = document.getElementById('stockChart').getContext('2d');
const stockChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
        datasets: [{
            label: 'Valor das Ações',
            data: [120, 150, 170, 180, 140, 160],
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});