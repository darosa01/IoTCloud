<?php

  header("Cache-Control: no-cache, must-revalidate");
  require_once "connection.php";
  require_once "api-functions.php";

?>
<!DOCTYPE html>
<html lang="en"> 
  <head>
      <title>Air Quality - Weather Master</title>
      
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
    <h1>Air Quality</h1>
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
        var chartData = getDataFromDate(values, toUglyDate(selectedDate))
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
          changeDay(':)', newDate);
        }
      }

      function toNiceDate(inputDate){
        var splitDate = inputDate.split('-');
        var niceDate = splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];
        return niceDate;
      }

      function toUglyDate(inputDate){
        var splitDate = inputDate.split('-');
        var uglyDate = splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];
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

        var PM10 = [];
        var O3 = [];
        var NO2 = [];
        var SO2 = [];

        values.forEach((elem) => {
          var datetime = elem['datetime'].split(' ');
          var date = datetime[0];
          var time = datetime[1];

          if(date == selectedDate){
            PM10.push({x: time, y: elem['PM10']});
            O3.push({x: time, y: elem['O3']});
            NO2.push({x: time, y: elem['NO2']});
            SO2.push({x: time, y: elem['SO2']});
          }
        });

        PM10.sort(sortDataByDate);
        O3.sort(sortDataByDate);
        NO2.sort(sortDataByDate);
        SO2.sort(sortDataByDate);

        var chartData = [
        {
          label: "PM10 (??g/m3)",
          data: PM10,
          borderColor: 'rgba(100, 0, 0, 1)',
          backgroundColor: 'rgba(100, 0, 0, 0.5)',
          lineTension: 0.2
        },
        {
          label: 'O3 (??g/m3)',
          data: O3,
          borderColor: 'rgba(100, 200, 255, 1)',
          backgroundColor: 'rgba(100, 200, 255, 0.5)',
          lineTension: 0.2
        },
        {
          label: 'NO2 (??g/m3)',
          data: NO2,
          borderColor: 'rgba(230, 0, 0, 1)',
          backgroundColor: 'rgba(230, 0, 0, 0.5)',
          lineTension: 0.2
        },
        {
          label: 'SO2 (??g/m3)',
          data: SO2,
          borderColor: 'rgba(130, 0, 130, 1)',
          backgroundColor: 'rgba(130, 0, 130, 0.5)',
          lineTension: 0.2
        }];

        if(PM10.length == 0 && O3.length == 0 && NO2.length == 0 && SO2.length == 0){
          chartData = [];
        }

        return chartData;
      }

      function loadChart(chartData){
        const config = {
          type: 'line',
          data: {
            datasets: chartData
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
                title: {
                  display: false,
                  text: 'Date'
                }
              },
              y: {
                title: {
                  display: true,
                  text: '??g/m3'
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

      var rawData = '<?php echo getAir($conn); ?>';
      var values = JSON.parse(rawData);

      var currentDate = new Date();
      var selectedDate = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0') + '-' + String(currentDate.getDate()).padStart(2, '0');

      document.getElementById('date-input').value = selectedDate;
      document.getElementById('current-date').innerHTML = toNiceDate(selectedDate);

      var chartData = getDataFromDate(values, selectedDate);
      console.log(chartData);
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