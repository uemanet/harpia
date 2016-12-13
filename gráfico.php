var DadosDoGrafico = {
    labels : dias,
    datasets : [
        {
            fillColor : "rgba(172,194,132,0.4)",
            strokeColor : "#ACC26D",
            pointColor : "#fff",
            pointStrokeColor : "#9DB86D",
            data : tempos
        }
    ]
};

var chartOptions = {
  //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
  scaleBeginAtZero: true,
  //Boolean - Whether grid lines are shown across the chart
  scaleShowGridLines: true,
  //Number - Width of the grid lines
  scaleGridLineWidth: 1,
  //Boolean - Whether to show horizontal lines (except X axis)
  scaleShowHorizontalLines: true,

  //bezierCurve: false,

  //bezierCurveTension: 0.2,

  //Boolean - Whether to show vertical lines (except Y axis)
  scaleShowVerticalLines: true,
  //Boolean - whether to make the chart responsive
  responsive: true,
  maintainAspectRatio: false
};
// get line chart canvas
var monitoramento = document.getElementById('grafico-tempo').getContext('2d');
// draw line chart
new Chart(monitoramento).Line(DadosDoGrafico, chartOptions);
