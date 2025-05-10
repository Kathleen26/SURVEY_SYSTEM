<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Analytics Results</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script> <!-- Plugin for labels -->
  <style>
  body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-image: linear-gradient(white, rgb(122, 166, 232));
      padding-top: 60px;
    }
    nav {
      background-color: hsl(198, 87%, 50%);
      width: 100%;
      padding: 20px;
      color: white;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .analytics-container {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: flex-start;
      padding: 30px;
      flex-wrap: nowrap;
      margin-top: 20px;
    }
    .analytics-left {
      background: linear-gradient(to bottom right, #c3e0f7, #a3c9f9);
      border-radius: 15px;
      padding: 30px;
      width: 50%;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .analytics-left h2 {
      font-size: 36px;
      background: #b7d9f7;
      padding: 14px 28px;
      border-radius: 25px;
      display: inline-block;
      font-weight: 900;
    }

    button {
      display: block;
      margin: 24px auto;
      text-align: center;
      padding: 14px 28px;
      font-size: 18px;
      font-weight: 600;
    }

    .description {
      background: #9cc0e5;
      padding: 24px;
      border-radius: 15px;
      margin: 24px 0;
      color: #001933;
      font-size: 18px;
    }

    .legend {
      margin: 20px 0;
    }

    .legend-item {
      display: flex;
      align-items: center;
      margin: 14px 0;
      font-size: 18px;
      font-weight: 600;
    }

    .legend-color {
      width: 25px;
      height: 25px;
      margin-right: 10px;
      border-radius: 4px;
    }

    .green {
      background-color: #12c468;
    }

    .red {
      background-color: #f44336;
    }

    .cta-box {
      background: #9cc0e5;
      padding: 20px;
      border-radius: 15px;
    }

    .cta-box p {
      margin-bottom: 15px;
      font-weight: bold;
      color: #001933;
    }

    .cta-box button {
      background-color: #1e90ff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      font-size: 16px;
      cursor: pointer;
    }

    .cta-box button:hover {
      background-color: #0b6fc2;
    }

    @media (max-width: 900px) {
      .analytics-container {
        flex-direction: column;
      }

      .analytics-left,
      .analytics-right {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<nav></nav>
  <div class="analytics-container">
    <div class="analytics-left">
      <h2>ANALYTICS RESULTS</h2>
      <div class="description">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vulputate nulla at ante rhoncus, vel efficitur felis condimentum. Proin odio odio.
      </div>

      <div class="legend">
        <div class="legend-item">
          <div class="legend-color green"></div>
          <strong>HIGH VOTES</strong>
        </div>
        <div class="legend-item">
          <div class="legend-color red"></div>
          <strong>LOW VOTES</strong>
        </div>
      </div>

      <div class="cta-box">
        <p>DO YOU WANT TO SEE THE RESULTS AND SUGGESTIONS PER AREA?</p>
        <button onclick="window.location.href='map.php'">CLICK HERE!</button>
      </div>
    </div>

    <div class="analytics-right">
      <canvas id="voteChart" style="width: 100%; height: 600px;"></canvas>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('voteChart').getContext('2d');

    const lowVotes = [3, 7, 16];
    const highVotes = [6, 13, 20];
    const totalVotes = lowVotes.map((low, i) => low + highVotes[i]);

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['CON UNO', 'CON DOS', 'NANGKA'],
        datasets: [
          {
            label: 'LOW VOTES',
            data: lowVotes,
            backgroundColor: '#f44336'
          },
          {
            label: 'HIGH VOTES',
            data: highVotes,
            backgroundColor: '#12c468'
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 5
            }
          }
        },
        plugins: {
          legend: {
            position: 'top',
            labels: {
              boxWidth: 20
            }
          },
          datalabels: {
            anchor: 'end',
            align: 'top',
            formatter: (value, context) => {
              const i = context.dataIndex;
              const total = totalVotes[i];
              const percent = ((value / total) * 100).toFixed(1);
              return percent + '%';
            },
            color: '#000',
            font: {
              weight: 'bold'
            }
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>
</body>
</html>
