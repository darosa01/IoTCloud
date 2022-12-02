<?php

  header("Cache-Control: no-cache, must-revalidate");
  require_once "connection.php";
  require_once "api-functions.php";

?>
<!DOCTYPE html>
<html lang="en"> 
  <head>
      <title>Temperature - Weather Master</title>
      
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
    <h1>Temperature</h1>
    <div class="search">
      <form action="javascript:void(0)" onsubmit="dateFromForm()">
        <span>Introduce date: </span>
        <input type="date" id="date-input">
        <button>Search</button>
      </form>
    </div>
    <div class="date" id="date">
      <div onclick="changeDay('-')" class="date-button">&lt;</div>
      <div id="current-date"></div>
      <div onclick="changeDay('+')" class="date-button">&gt;</div>
    </div>
    <div class="chart" id="canvas-box">
      <canvas id="canvas"></canvas>
    </div>
    <div class="no-data" id="no-data">
      There is no data for the selected date
    </div>
    <div class="raw-data-box" id="raw-data-box">
      <hr>
      <details>
        <summary>Raw data</summary>
        <code id="raw-data"></code>
      </details>
    </div>
    <script>

      function changeDay(op, specificDate = undefined){
        var date = document.getElementById('current-date').innerHTML;
        var uglyDate = toUglyDate(date);
        if(specificDate !== undefined){
          uglyDate = specificDate;
        }
        var newDate = new Date(uglyDate);

        if(specificDate === undefined){
          if(op == "+"){
            newDate.setDate(newDate.getDate() + 1);
          } else {
            newDate.setDate(newDate.getDate() - 1);
          }
        }

        var selectedDate = String(newDate.getDate()).padStart(2, '0') + '-' + String(newDate.getMonth() + 1).padStart(2, '0') + '-' + newDate.getFullYear();
        document.getElementById('current-date').innerHTML = selectedDate;
        var chartData = getDataFromDate(values, toUglyDate(selectedDate));
        if(chartData.length == 0){
          document.getElementById('no-data').style.display = 'block';
          document.getElementById('canvas-box').style.display = 'none';
          document.getElementById('raw-data-box').style.display = 'none';
        } else {
          document.getElementById('no-data').style.display = 'none';
          document.getElementById('canvas-box').style.display = 'block';
          document.getElementById('raw-data').innerText = JSON.stringify(chartData);
          document.getElementById('raw-data-box').style.display = 'block';
          loadChart(chartData);
        }
      }

      function dateFromForm(){
        var newDate = document.getElementById('date-input').value;
        if(newDate){
          changeDay('', newDate);
        }
      }

      function toNiceDate(inputDate){
        var splitDate = inputDate.split('-');
        var niceDate = splitDate[2].padStart(2, '0') + '-' + splitDate[1].padStart(2, '0') + '-' + splitDate[0];
        return niceDate;
      }

      function toUglyDate(inputDate){
        var splitDate = inputDate.split('-');
        var uglyDate = splitDate[2] + '-' + splitDate[1].padStart(2, '0') + '-' + splitDate[0].padStart(2, '0');
        return uglyDate;
      }

      function sortDataByDate(a, b){
        let first = new Date("December 17, 1995 " + a.x);
        let second = new Date("December 17, 1995 " + b.x);
        if(first < second){
          return -1;
        }
        if(first > second){
          return 1;
        }
        return 0;
      }

      function getDataFromDate(values, selectedDate){

        var chartData = [];

        values.forEach((elem) => {
          var datetime = elem['datetime'].split(' ');
          var date = datetime[0];
          var time = datetime[1];

          if(date == selectedDate){
            chartData.push({x: time, y: elem['value']});
          }
        });

        chartData.sort(sortDataByDate);

        return chartData;
      }

      function loadChart(chartData){
        const config = {
          type: 'line',
          data: {
            datasets: [{
              label: 'ºC',
              data: chartData,
              borderColor: 'rgba(230, 0, 0, 1)',
              backgroundColor: 'rgba(230, 0, 0, 0.5)',
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

        if(window.chart !== undefined){
          window.chart.destroy();
        } 
        var ctx = document.getElementById("canvas").getContext("2d");
        window.chart = new Chart(ctx, config);
      }

      var rawData = '<?php echo getTemperature($conn); ?>';
      var values = JSON.parse(rawData);

      var currentDate = new Date();
      var selectedDate = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0') + '-' + String(currentDate.getDate()).padStart(2, '0');

      document.getElementById('date-input').value = selectedDate;
      document.getElementById('current-date').innerHTML = toNiceDate(selectedDate);

      var chartData = getDataFromDate(values, selectedDate);
      if(chartData.length == 0){
        document.getElementById('no-data').style.display = 'block';
        document.getElementById('canvas-box').style.display = 'none';
        document.getElementById('raw-data-box').style.display = 'none';
      } else {
        document.getElementById('no-data').style.display = 'none';
        document.getElementById('canvas-box').style.display = 'block';
        document.getElementById('raw-data').innerText = JSON.stringify(chartData);
        document.getElementById('raw-data-box').style.display = 'block';
        loadChart(chartData);
      }  
    </script>
  </body>
</html>
<?php
  $conn->close();
?>