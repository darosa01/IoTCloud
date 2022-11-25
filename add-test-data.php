<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" href="assets/icons/icon.ico" media="(prefers-color-scheme: light)">
  <link rel="icon" href="assets/icons/icon-dark.ico" media="(prefers-color-scheme: dark)">

  <title>Add Test Data - Weather Master</title>
</head>
<body>
  <div>
    <input type="date" id="date-input">
    <button id="date-button">Add new data</button>
  </div>
  <script>

    function getRndInteger(min, max) {
      return Math.floor(Math.random() * (max - min + 1) ) + min;
    }

    function generateData(){
      var rawDate = document.getElementById('date-input').value;

      if(!rawDate){
        return;
      }

      var generationRate = 15; // in minutes (default=15)
      var iterations = Math.floor((60*24)/generationRate);

      var newDate = new Date(rawDate + ' 00:00:24');
      var timestamp = newDate.getFullYear() + '-' + (newDate.getMonth() + 1) + '-' + newDate.getDate() + ' ' + newDate.getHours() + ':' + newDate.getMinutes() + ':' + newDate.getSeconds();

      for(let i = 0; i < iterations; i++){

        // Temperature
        var randomTemperature = getRndInteger(10,30);

        // Humidity
        var randomHumidity = getRndInteger(10,90);

        // Air Quality
        var randomPM10 = getRndInteger(10,40);
        var randomO3 = getRndInteger(160,180);
        var randomNO2 = getRndInteger(10,40);
        var randomSO2 = getRndInteger(1,10);

        fetch('./api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            timestamp: timestamp,
            temperature: randomTemperature, 
            humidity: randomHumidity,
            air: {
              PM10: randomPM10,
              O3: randomO3,
              NO2: randomNO2,
              SO2: randomSO2
            }
          })
        });

        newDate.setMinutes(newDate.getMinutes() + generationRate);
        timestamp = newDate.getFullYear() + '-' + (newDate.getMonth() + 1) + '-' + newDate.getDate() + ' ' + newDate.getHours() + ':' + newDate.getMinutes() + ':' + newDate.getSeconds();
      }
    }

    var dateButton = document.getElementById('date-button');
    dateButton.addEventListener('click', generateData);

  </script>
</body>
</html>
