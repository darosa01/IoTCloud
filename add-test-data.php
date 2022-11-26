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
  <br/>
  <div>
    <a href=".">Back to main page</a>
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

      // Temperature
      var tempLimit = [0, 40];
      var randomTemperature = getRndInteger(tempLimit[0], tempLimit[1]);

      // Humidity
      var humidityLimit = [0, 100];
      var randomHumidity = getRndInteger(humidityLimit[0], humidityLimit[1]);

      for(let i = 0; i < iterations; i++){

        var variacion = Math.random();

        if(getRndInteger(0, 9) % 2 != 0){
        variacion = variacion * -1;
        }

        randomTemperature += variacion;
        randomHumidity += (variacion * 2);

        if(randomTemperature < tempLimit[0]){
          randomTemperature = tempLimit[0];
        }
        if(randomTemperature > tempLimit[1]){
          randomTemperature = tempLimit[1];
        }

        if(randomHumidity < humidityLimit[0]){
          randomHumidity = humidityLimit[0];
        }
        if(randomHumidity > humidityLimit[1]){
          randomHumidity = humidityLimit[1];
        }

        randomTemperature = Math.round(randomTemperature * 100) / 100;
        randomHumidity = Math.round(randomHumidity * 100) / 100;

        // Air Quality
        var randomPM10 = getRndInteger(10,15);
        var randomO3 = getRndInteger(160,165);
        var randomNO2 = getRndInteger(10,15);
        var randomSO2 = getRndInteger(1,6);

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
      alert('New data created successfully!');
    }

    var dateButton = document.getElementById('date-button');
    dateButton.addEventListener('click', generateData);

  </script>
</body>
</html>
