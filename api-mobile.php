<?php

require_once "connection.php"; /* returns $conn */ /* !!! remember to close the connection !!! */
require_once "api-functions.php";

$output = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if(isset($_GET['temperature'])){
    if(isset($_GET['timestamp'])){
      $output = $output . PHP_EOL . pushTemperature($conn, $_GET['temperature'], $_GET['timestamp']);
    } else {
      $output = $output . PHP_EOL . pushTemperature($conn, $_GET['temperature']);
    }
  }
  if(isset($_GET['humidity'])){
    if(isset($_GET['timestamp'])){
      $output = $output . PHP_EOL . pushHumidity($conn, $_GET['humidity'], $_GET['timestamp']);
    } else {
      $output = $output . PHP_EOL . pushHumidity($conn, $_GET['humidity']);
    };
  }
  if(isset($_GET['air'])){
    $airRaw = explode("-", $_GET['air']);
    if(count($airRaw) == 4){
      $airData = '{"PM10": '.$airRaw[0].', "O3": '.$airRaw[1].', "NO2": '.$airRaw[2].', "SO2": '.$airRaw[3].'}';

      if(isset($_GET['timestamp'])){
        $output = $output . PHP_EOL . pushAir($conn, $airData, $_GET['timestamp']);
      } else {
        $output = $output . PHP_EOL . pushAir($conn, $airData);
      }
    }
  }

  echo $output;

} else {
  echo "Request method not supported. Please use GET.";
}

$conn->close();
?>