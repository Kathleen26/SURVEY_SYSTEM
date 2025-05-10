<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Map Results</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: linear-gradient(to bottom, #ffffff, #7aa6e8);
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    nav {
      background-color: hsl(198, 87%, 50%);
      width: 100%;
      padding: 20px;
      color: white;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 100px;
      flex-wrap: wrap;
    }

    .info-box,
    #map {
      width: 500px;
      height: 500px;
      border-radius: 25px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: 0.3s;
    }

    .info-box {
      background: linear-gradient(to bottom right, #c3e0f7, #a3c9f9);
      padding: 30px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .info-box h2 {
      font-size: 28px;
      background: #b7d9f7;
      padding: 10px 20px;
      border-radius: 25px;
      font-weight: 900;
      margin-bottom: 20px;
      display: inline-block;
    }

    .info-box p {
      background-color: rgba(255, 255, 255, 0.3);
      padding: 15px;
      border-radius: 15px;
      line-height: 1.5;
      margin-bottom: 20px;
    }

    .legend {
      margin-top: 10px;
    }

    .legend-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .legend-color {
      width: 20px;
      height: 20px;
      margin-right: 10px;
      border-radius: 4px;
    }

    .legend-color.green {
      background-color: #12c468;
    }

    .legend-color.red {
      background-color: #f44336;
    }

    .buttons {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }

    .buttons button {
      background-color: #0ea5e9;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 20px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .buttons button:hover {
      background-color: #0c87cc;
    }

    #map {
      border: 2px solid #000;
    }

    @media (max-width: 1100px) {
      .container {
        flex-direction: column;
      }

      .info-box, #map {
        width: 90%;
        height: auto;
      }
    }
  </style>
</head>
<body>

<nav></nav>

<div class="container">
  <div class="info-box">
    <div>
      <h2>MAP RESULTS</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vulputate nulla at ante rhoncus, vel efficitur felis condimentum. Proin odio odio.</p>
      <div class="legend">
        <div class="legend-item">
          <div class="legend-color green"></div><strong>HIGH VOTES</strong>
        </div>
        <div class="legend-item">
          <div class="legend-color red"></div><strong>LOW VOTES</strong>
        </div>
      </div>
    </div>
    <div class="buttons">
      <button onclick="goBack()">BACK</button>
      <button onclick="goNext()">NEXT</button>
    </div>
  </div>

  <div id="map"></div>
</div>

<script>
  const map = L.map('map').setView([14.6507, 121.1029], 14);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © OpenStreetMap contributors'
  }).addTo(map);

  function goBack() {
    window.history.back();
  }

  function goNext() {
    alert("Next page logic goes here.");
  }

  // Define green and red icons
  const greenIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  const redIcon = new L.Icon({
    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41]
  });

  // Show user location with green icon and bind popup with user's answer
  function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }

  const userAnswer = getQueryParam('user_answer') || 'Your answer';

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const userMarker = L.marker([userLat, userLng], { icon: greenIcon }).addTo(map);
        userMarker.bindPopup('Answer: ' + userAnswer);
        map.setView([userLat, userLng], 14);
      },
      function(error) {
        console.warn("Geolocation error:", error.message);
      }
    );
  } else {
    console.warn("Geolocation is not supported by this browser.");
  }

</script>



</body>
</html>
