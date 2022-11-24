<!DOCTYPE html>
<html lang="ca"> 
  <head>
      <title>IoT Project</title>
      
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link rel="icon" href="assets/icons/icon.ico" media="(prefers-color-scheme: light)">
      <link rel="icon" href="assets/icons/icon-dark.ico" media="(prefers-color-scheme: dark)">
      <link id="theme-style" rel="stylesheet" href="/assets/css/style.css">

  </head>
  <body>
    <header>
      <div class="logo">
        <a href="..">
          <img src="assets/img/logo.png">
        </a>
      </div>
      <nav class="navbar">
        <div>
          <a href="..">How to use</a>
        </div>
        <div>
          <a href="../temperature.php">Temperature</a>
        </div>
        <div>
          <a href="../humidity.php">Humidity</a>
        </div>
        <div>
          <a href="../air-quality.php">Air Quality</a>
        </div>
      </nav>
    </header>
    <h1>Humidity</h1>
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
    <!-- <div><?php echo getHumidity($conn, 10) ?></div> -->
    <script>
      var selectedDate = '<?php echo $selectedDate; ?>';

      if(selectedDate){

        var splitDate = selectedDate.split('-');
        var niceDate = splitDate[2] + '-' + splitDate[1] + '-' + splitDate[0];

        var data = '<?php echo getHumidity($conn); ?>';
        var values = JSON.parse(data);

        var chartData = [];

        values.forEach((elem) => {
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
                  text: 'Humidity (%)'
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