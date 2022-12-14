<?php header("Cache-Control: no-cache, must-revalidate"); ?>
<!DOCTYPE html>
<html lang="ca"> 
  <head>
      <title>Weather Master</title>
      
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="description" content="">
      <meta name="keywords" content="">
      <link rel="icon" href="assets/icons/icon.ico" media="(prefers-color-scheme: light)">
      <link rel="icon" href="assets/icons/icon-dark.ico" media="(prefers-color-scheme: dark)">
      <link id="theme-style" rel="stylesheet" href="assets/css/style.css">

      <script type="module" src="https://md-block.verou.me/md-block.js"></script>
  </head>
  <body>
    <?php include "header.php"; ?>
    <section class="how-to-section">
      <md-block>
        # How to use
        ___
        ## Check data

        ### Temperature
        In order to check the temperature data you just have to access the [Temperature](temperature.php) page.

        ### Humidity
        In order to check the humidity data you just have to access the [Humidity](humidity.php) page.

        ### Air quality
        In order to check the air quality data you just have to access the [Air Quality](air-quality.php) page.

        ### Comparison
        In this section you can compare the data of each group with that of the previous year. You can check it by accessing the [Comparison](comparison.php) page.
        ___
        ## API
        Our solution provides an API that allows you to get and send data to/from all kinds of machines 
        that support HTTP connections. This is specially useful to connect with the mobile app because it 
        allows the update of the database stored information, as well as the retrival of old data.

        ### Send data
        You can easily send new data to the cloud by making a POST request with specific in the body.
        <br/><br/>
        Here is an example:
        ```http
        POST http://url.com/api.php
        Content-Type: application/json

        {
          "timestamp": "2022-11-25 15:27:42",
          "temperature": 25,
          "humidity": 85,
          "air": {
            "PM10": 40,
            "03": 180,
            "NO2": 40,
            "SO2": 10
          }
        }
        ```
        The data must be sent in a JSON format and the available parameters are the following:
        - ***timestamp***: a string containing the date and time in the format (YYYY-MM-DD HH:mm:ss). If not specified, 
        the timestamp will be automatically added by the database.
        - ***temperature***: a numeric value expressing the temperature in ??C.
        - ***humidity***: a numeric value expressing the humidity in %.
        - ***air***: a JSON containing numeric values expressing the PM10, O3, NO2 and SO2 in ??g/m3.

        You can make requests with the number of parameters you want. It is not necessary to use them all.

        ### Get data
        You can easily get data from the cloud by making a GET request with specific parameters.
        <br/><br/>
        Here is an example:
        ```http
        GET http://url.com/api.php?data=temperature&maxvalues=20
        ```
        The avaliable parameters are the following:
        - ***data***: you can use this parameter in order to select the type of data you want to get (temperature, humidity or air).
        - ***maxvalues***: allows you to get the n number of last records, if not specified, all records are returned.
        
        ___
        ## Developers
        In order to test if the platform is working well, you can generate random data for a specific day
        using [this](add-test-data.php) page.
        <br/>
        If you want to delete all the previously generated data, click on the button: <button onclick="deleteData()">Delete data</button>
        ___
        <br/>
      </md-block>
    </section>
    <footer>
      <small>Developed by David Romero and Gonzalo Garc??a</small>
      <br/>
      <small>IoT Lab - Universitat Aut??noma de Barcelona</small>
      <br/>
      <small>2022/2023</small>
    </footer>
    <script>
      function deleteData(){
        let confirmed = confirm("Are you sure you want to delete all stored data?");
        if(confirmed){
          fetch("remove-all-data.php");
        }
      }
    </script>
  </body>
</html>