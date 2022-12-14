<?php

require_once "connection.php"; /* returns $conn */ /* !!! remember to close the connection !!! */
require_once "api-functions.php";

$maxValues = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if(isset($_GET['maxvalues'])){
    $maxValues = $_GET['maxvalues'];
  }

  if(isset($_GET['data'])){
    switch($_GET['data']){
      case 'temperature':
        echo getTemperature($conn, $maxValues);
        break;
      case 'humidity':
        echo getHumidity($conn, $maxValues);
        break;
      case 'air':
        echo getAir($conn, $maxValues);
        break;
      default:
        echo "Indicate the desired data using URL params (ex. url.com/api.php?data=temperature&maxvalues=20)";
        break;
    }
  }
  else{
    echo "Indicate the desired data using URL params (ex. url.com/api.php?data=temperature&maxvalues=20)";
  }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Input Log
  $body = file_get_contents('php://input');
  $file = "testBLE.txt";
  file_put_contents($file, $body, FILE_APPEND);
  // ------------------------------------------

  $body = json_decode($body, true);
  if(!empty($body)){
    if($body['temperature']){
      if($body['timestamp']){
        pushTemperature($conn, $body['temperature'], $body['timestamp']);
      } else {
        pushTemperature($conn, $body['temperature']);
      }
    }
    if($body['humidity']){
      if($body['timestamp']){
        pushHumidity($conn, $body['humidity'], $body['timestamp']);
      } else {
        pushHumidity($conn, $body['humidity']);
      };
    }
    if($body['air']){
      if($body['timestamp']){
        pushAir($conn, $body['air'], $body['timestamp']);
      } else {
        pushAir($conn, $body['air']);
      }
    }
    echo "Done";
  } else {
    echo "Data not received";
  }
} else {
  echo "Request method not supported. Please use GET or POST.";
}

$conn->close();

?>