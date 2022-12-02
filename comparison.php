<?php

header("Cache-Control: no-cache, must-revalidate");
require_once "connection.php";
require_once "api-functions.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Comparison - Weather Master</title>
        
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="assets/icons/icon.ico" media="(prefers-color-scheme: light)">
    <link rel="icon" href="assets/icons/icon-dark.ico" media="(prefers-color-scheme: dark)">
    <link id="theme-style" rel="stylesheet" href="assets/css/style.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>
    <?php include "header.php"; ?>
    <h1>Comparison</h1>

    <div class="group-box">
      <h3>Temperature</h3>
      <div class="chart" id="canvas-temp-box">
        <canvas id="canvas-temp"></canvas>
      </div>
    </div>
    <div class="group-box">
      <h3>Humidity</h3>
      <div class="chart" id="canvas-humidity-box">
        <canvas id="canvas-humidity"></canvas>
      </div>
    </div>
    <div class="group-box">
      <h3>Air Quality - PM10</h3>
      <div class="chart" id="canvas-pm10-box">
        <canvas id="canvas-pm10"></canvas>
      </div>
    </div>
    <div class="group-box">
      <h3>Air Quality - O3</h3>
      <div class="chart" id="canvas-o3-box">
        <canvas id="canvas-o3"></canvas>
      </div>
    </div>
    <div class="group-box">
      <h3>Air Quality - NO2</h3>
      <div class="chart" id="canvas-no2-box">
        <canvas id="canvas-no2"></canvas>
      </div>
    </div>
    <div class="group-box">
      <h3>Air Quality - SO2</h3>
      <div class="chart" id="canvas-so2-box">
        <canvas id="canvas-so2"></canvas>
      </div>
    </div>

    <script>
      /*
       * IDEA: Crear un gráfico para cada grupo comparando los datos disponibles desde la fecha actual
       * hasta un año atrás con los del año anterior.      
       */

      let rawTemperatureData = '<?php echo getTemperature($conn); ?>';
      let rawHumidityData = '<?php echo getHumidity($conn); ?>';
      let rawAirData = '<?php echo getAir($conn); ?>';

      let temperatureData = JSON.parse(rawTemperatureData);
      let humidityData = JSON.parse(rawHumidityData);
      let airData = JSON.parse(rawAirData);


      // Media de temperaturas por dia 

      let temperaturesAux = {};

      temperatureData.forEach(elem => {
        let datetime = elem['datetime'].split(' ');
        let date = datetime[0];
        let time = datetime[1];

        if(temperaturesAux[date]){
          temperaturesAux[date].counter++;
          temperaturesAux[date].value += elem['value'];
        } else {
          temperaturesAux[date] = {};
          temperaturesAux[date].counter = 1;
          temperaturesAux[date].value = elem['value'];
        }
      });

      let temperatures = [];

      Object.keys(temperaturesAux).forEach(key => {
        temperatures.push({
          date: key,
          value: roundDecimals(temperaturesAux[key].value/temperaturesAux[key].counter)
        });
      });


      // Media de humedad por dia 

      let humidityAux = {};

      humidityData.forEach(elem => {
        let datetime = elem['datetime'].split(' ');
        let date = datetime[0];
        let time = datetime[1];

        if(humidityAux[date]){
          humidityAux[date].counter++;
          humidityAux[date].value += elem['value'];
        } else {
          humidityAux[date] = {};
          humidityAux[date].counter = 1;
          humidityAux[date].value = elem['value'];
        }
      });

      let humidity = [];

      Object.keys(humidityAux).forEach(key => {
        humidity.push({
          date: key,
          value: roundDecimals(humidityAux[key].value/humidityAux[key].counter)
        });
      });


      // Media de calidad del aire por dia

      let airAux = {};

      airData.forEach(elem => {
        let datetime = elem['datetime'].split(' ');
        let date = datetime[0];
        let time = datetime[1];

        if(airAux[date]){
          airAux[date].counter++;
          airAux[date].PM10 += elem['PM10'];
          airAux[date].O3 += elem['O3'];
          airAux[date].NO2 += elem['NO2'];
          airAux[date].SO2 += elem['SO2'];
        } else {
          airAux[date] = {};
          airAux[date].counter = 1;
          airAux[date].PM10 = elem['PM10'];
          airAux[date].O3 = elem['O3'];
          airAux[date].NO2 = elem['NO2'];
          airAux[date].SO2 = elem['SO2'];
        }
      });

      let air = [];

      Object.keys(airAux).forEach(key => {
        air.push({
          date: key,
          PM10: roundDecimals(airAux[key].PM10/airAux[key].counter),
          O3: roundDecimals(airAux[key].O3/airAux[key].counter),
          NO2: roundDecimals(airAux[key].NO2/airAux[key].counter),
          SO2: roundDecimals(airAux[key].SO2/airAux[key].counter),
        });
      });


      // Establecimiento de fechas y limites

      let date = new Date();
      let currentDate = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
      let pastYearDate = new Date();
      pastYearDate.setFullYear(pastYearDate.getFullYear() - 1);
      let limitDate = new Date();
      limitDate.setFullYear(limitDate.getFullYear() - 2);


      // Separacion de temperaturas por fechas

      let firstGroupTemperatures = []; // current year
      let secondGroupTemperatures = []; // past year

      temperatures.forEach(elem => {
        let elemDate = new Date(elem.date);
        let splitDate = elem.date.split('-');

        let newElem = {
          x: (splitDate[2] + '-' + splitDate[1]),
          y: elem.value
        }

        if(elemDate <= date && elemDate > pastYearDate){
          firstGroupTemperatures.push(newElem);
        }
        else if(elemDate >= pastYearDate && elemDate > limitDate){
          secondGroupTemperatures.push(newElem);
        }
      });


      // Separacion de humedades por fechas

      let firstGroupHumidity = []; // current year
      let secondGroupHumidity = []; // past year

      humidity.forEach(elem => {
        let elemDate = new Date(elem.date);
        let splitDate = elem.date.split('-');

        let newElem = {
          x: (splitDate[2] + '-' + splitDate[1]),
          y: elem.value
        }

        if(elemDate <= date && elemDate > pastYearDate){
          firstGroupHumidity.push(newElem);
        }
        else if(elemDate >= pastYearDate && elemDate > limitDate){
          secondGroupHumidity.push(newElem);
        }
      });


      // Separacion de calidad del aire por fechas

      let firstGroupPM10 = []; // current year
      let secondGroupPM10 = []; // past year

      let firstGroupO3 = [];
      let secondGroupO3 = [];

      let firstGroupNO2 = [];
      let secondGroupNO2 = [];

      let firstGroupSO2 = [];
      let secondGroupSO2 = [];

      air.forEach(elem => {
        let elemDate = new Date(elem.date);
        let splitDate = elem.date.split('-');

        let shortDate = splitDate[2] + '-' + splitDate[1];

        if(elemDate <= date && elemDate > pastYearDate){
          firstGroupPM10.push({
            x: shortDate,
            y: elem.PM10
          });
          firstGroupO3.push({
            x: shortDate,
            y: elem.O3
          });
          firstGroupNO2.push({
            x: shortDate,
            y: elem.NO2
          });
          firstGroupSO2.push({
            x: shortDate,
            y: elem.SO2
          });
        }
        else if(elemDate >= pastYearDate && elemDate > limitDate){
          secondGroupPM10.push({
            x: shortDate,
            y: elem.PM10
          });
          secondGroupO3.push({
            x: shortDate,
            y: elem.O3
          });
          secondGroupNO2.push({
            x: shortDate,
            y: elem.NO2
          });
          secondGroupSO2.push({
            x: shortDate,
            y: elem.SO2
          });
        }
      });


      // Creacion del grafico de temperatura

      const configTemp = {
        type: 'line',
        data: {
          datasets: [{
            label: 'Actual period',
            data: firstGroupTemperatures,
            borderColor: 'rgba(230, 0, 0, 1)',
            backgroundColor: 'rgba(230, 0, 0, 0.5)',
            lineTension: 0.2
          }, {
            label: 'Past period',
            data: secondGroupTemperatures,
            borderColor: 'rgba(230, 64, 64, 1)',
            backgroundColor: 'rgba(230, 64, 64, 0.5)',
            lineTension: 0.2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
              position: 'top',
            }
          },
          scales: {
            x: {
              /*type: 'time',
              time: {
                // Luxon format string
                tooltipFormat: 'DD T'
              },*/
              title: {
                display: false,
                text: 'Date'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Temperature (ºC)'
              }
            }
          }
        },
      }

      var ctx = document.getElementById("canvas-temp").getContext("2d");
      window.temperatureChart = new Chart(ctx, configTemp);


      // Creacion del grafico de humedad

      const configHumidity = {
        type: 'line',
        data: {
          datasets: [{
            label: 'First period',
            data: firstGroupHumidity,
            borderColor: 'rgba(0, 160, 255, 1)',
            backgroundColor: 'rgba(0, 160, 255, 0.5)',
            lineTension: 0.2
          }, {
            label: 'Second period',
            data: secondGroupHumidity,
            borderColor: 'rgba(100, 200, 255, 1)',
            backgroundColor: 'rgba(100, 200, 255, 0.5)',
            lineTension: 0.2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
              position: 'top',
            }
          },
          scales: {
            x: {
              /*type: 'time',
              time: {
                // Luxon format string
                tooltipFormat: 'DD T'
              },*/
              title: {
                display: false,
                text: 'Date'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Humidity (%)'
              }
            }
          }
        },
      }

      var ctx = document.getElementById("canvas-humidity").getContext("2d");
      window.humidityChart = new Chart(ctx, configHumidity);


      // Creacion del grafico de PM10

      const configPM10 = {
        type: 'line',
        data: {
          datasets: [{
            label: 'First period',
            data: firstGroupPM10,
            borderColor: 'rgba(0, 160, 255, 1)',
            backgroundColor: 'rgba(0, 160, 255, 0.5)',
            lineTension: 0.2
          }, {
            label: 'Second period',
            data: secondGroupPM10,
            borderColor: 'rgba(100, 200, 255, 1)',
            backgroundColor: 'rgba(100, 200, 255, 0.5)',
            lineTension: 0.2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
              position: 'top',
            }
          },
          scales: {
            x: {
              /*type: 'time',
              time: {
                // Luxon format string
                tooltipFormat: 'DD T'
              },*/
              title: {
                display: false,
                text: 'Date'
              }
            },
            y: {
              title: {
                display: true,
                text: 'µg/m3'
              }
            }
          }
        },
      }

      var ctx = document.getElementById("canvas-pm10").getContext("2d");
      window.PM10Chart = new Chart(ctx, configPM10);


      // Funciones auxiliares

      function roundDecimals(num){
        return Math.round((num + Number.EPSILON) * 100) / 100;
      }

    </script>
  </body>
</html>
<?php

  $conn->close();

?>