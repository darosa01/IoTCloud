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
    </script>
  </body>
</html>