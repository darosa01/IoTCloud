<!DOCTYPE html>
<html lang="ca"> 
  <head>
      <title>IoT Project</title>
      
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="">
      <meta name="author" content="">
      <meta name="description" content="">
      <meta name="keywords" content="">
      <link rel="icon" href="assets/icons/icon.ico" media="(prefers-color-scheme: light)">
      <link rel="icon" href="assets/icons/icon-dark.ico" media="(prefers-color-scheme: dark)">
      <link id="theme-style" rel="stylesheet" href="/assets/css/style.css">

      <script type="module" src="https://md-block.verou.me/md-block.js"></script>
  </head>
  <body>
    <header>
      <div class="logo">
        <a href="">
          <img src="assets/img/logo.png">
        </a>
      </div>
      <nav class="navbar">
        <div>
          <a href="">How to use</a>
        </div>
        <div>
          <a href="temperature.php">Temperature</a>
        </div>
        <div>
          <a href="humidity.php">Humidity</a>
        </div>
        <div>
          <a href="air-quality.php">Air Quality</a>
        </div>
      </nav>
    </header>
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
        - ***temperature***: a numeric value expressing the temperature in ºC.
        - ***humidity***: a numeric value expressing the humidity in %.
        - ***air***: a JSON containing numeric values expressing the PM10, O3, NO2 and SO2 in µg/m3.

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
        <br/>
      </md-block>
    </section>
  </body>
</html>