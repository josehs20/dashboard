// Set new default font family and font color to mimic Bootstrap's default styling
// Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
// Chart.defaults.global.defaultFontColor = '#858796';


//consulta ajax
function consultaGraficoPie() {
  $.ajax({
    url: "/grafico-pie",
    type: "GET",
    dataType: 'json',
  }).done(function (response) {
    let data = response;
    montaGraficoPie(data);
  })
}

$(function () {
  consultaGraficoPie();
})

// Pie Chart Example
function montaGraficoPie(data) {
  let dadosGrafico = [];
  let total = 0;

  Object.keys(data).forEach((item) => {
    dadosGrafico.push({
      especies: item,
      cores: data[item].cor,
      valores: data[item].qtd,
    })

    total += parseInt(data[item].qtd);
  })

  document.getElementById('totalGraficoPie').innerText = 'Total: ' + total;
  
  var ctx = document.getElementById("graficoPie");

  var graficoPie = new Chart(ctx.getContext('2d'), {
    type: 'doughnut',
    data: {
      labels: dadosGrafico.map(function (e) { return e.especies; }),//["Dinheiro", "Cartão", "Ticket", 'vareados'],
      datasets: [{

        data: dadosGrafico.map(function (e) { return e.valores; }),
        backgroundColor: dadosGrafico.map(function (e) { return e.cores; }),
        hoverBackgroundColor: dadosGrafico.map(function (e) { return e.cores; }),
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });
}
