<?php

  require_once "connection.php";
  require_once "api-functions.php";

  $selectedDate = "";

  if(isset($_POST['date'])){
    $selectedDate = $_POST['date'];
  }

?>
<!DOCTYPE html>
<html lang="ca"> 
  <head>
      <title>IoT Project</title>
      
      <!-- Meta -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link rel="shortcut icon" href="favicon.ico">
      <link id="theme-style" rel="stylesheet" href="/assets/css/style.css">

      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>
    <nav class="navbar">
      <div>
        <a href="/">Main</a>
      </div>
      <div>
        <a href="/temperature.php">Temperature</a>
      </div>
      <div>
        <a href="/humidity.php">Humidity</a>
      </div>
      <div>
        <a href="/air-quality.php">Air Quality</a>
      </div>
    </nav>
    <h1>Temperature</h1>
    <div class="search">
      <form method="post">
        <span>Introduce date: </span>
        <input type="date" id="date-input" name="date">
        <button>Search</button>
      </form>
    </div>
    <div>
      <canvas id="canvas"></canvas>
    </div>
    <!-- <div><?php echo getTemperature($conn, 10) ?></div> -->
    <script>
      var selectedDate = '<?php echo $selectedDate; ?>';

      if(selectedDate){

        var splitDate = selectedDate.split('-');
        var niceDate = splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];

        var data = '<?php echo getTemperature($conn); ?>';
        var temperatures = JSON.parse(data);

        var chartData = [];

        temperatures.forEach((elem) => {
          var datetime = elem['datetime'].split(' ');
          var date = datetime[0];
          var time = datetime[1];

          if(date == selectedDate){
            chartData.push({x: time, y: elem['value']});
          }
        });

        const config = {
          type: 'line',
          data: {
            datasets: [{
              data: chartData
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: false,
                position: 'top',
              },
              title: {
                display: true,
                text: niceDate
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
                  text: 'Temperature (Cº)'
                }
              }
            }
          },
        }

        var ctx = document.getElementById("canvas").getContext("2d");
        window.chart = new Chart(ctx, config);
      }      
    </script>
  </body>
</html>