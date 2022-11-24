<?php

require_once "connection.php"; /* returns $conn */ /* !!! remember to close the connection !!! */
require_once "api-functions.php";

$maxValues = 10;

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

if(isset($_POST)){
  if(isset($_POST['temperature'])){
    pushTemperature($conn, $_POST['temperature']);
  }
  if(isset($_POST['humidity'])){
    pushHumidity($conn, $_POST['humidity']);
  }
  if(isset($_POST['air'])){
    pushAir($conn, $_POST['air']);
  }
}

$conn->close();

?>