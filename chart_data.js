window.onload = function () {
  Chart.defaults.global.legend.display = false;
  const ctx = document.querySelector("#chart").getContext('2d');
  var aqi = document.querySelector("#hidden-aqi").innerHTML;
  var quality = document.querySelector("#hidden-quality").innerHTML;
  var economy = document.querySelector("#hidden-economy").innerHTML;
  var outdoors = document.querySelector("#hidden-outdoors").innerHTML;
  var healthcare = document.querySelector("#hidden-healthcare").innerHTML;
  var education = document.querySelector("#hidden-education").innerHTML;
  var safety = document.querySelector("#hidden-safety").innerHTML;
  var housing = document.querySelector("#hidden-housing").innerHTML;

  const chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Envrionmental Quality', 'Outdoors', 'Economy', 'Healthcare', 'Education', 'Housing', 'Safety'],
      datasets: [{
        label: 'Rating Out of 10',
        data: [quality, outdoors, economy, healthcare, education, housing, safety],
        backgroundColor: [
          '#87D68D',
          '#87D68D',
          '#87D68D',
          '#87D68D',
          '#87D68D',
          '#87D68D',
          '#87D68D',
        ],
      }]
    },
    options: {
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            suggestedMax: 10
          }
        }]
      },
      responsive: true
    }
  });

  document.querySelector("#hover-quality").onmouseenter = function() {
    document.querySelector("#summary").style.display = "block";
  }

  document.querySelector("#hover-quality").onmouseleave = function() {
    document.querySelector("#summary").style.display = "none";
  }
}
