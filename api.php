<?php

/* Database connection */

$servername = "localhost";
$username = "root";
$password = "";
$database = "weather";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/* End Database connection */

/* Database functions */

function pushTemperature($conn, $data){
  /* ID and timestamp are added by the database */
  if(!$ssql = $conn->prepare("INSERT INTO `temperature` (`value`) VALUES (?);")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("i", intval($data))){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la temperatura a la base de datos");
  }   
}

function pushHumidity($conn, $data){
  /* ID and timestamp are added by the database */
  if(!$ssql = $conn->prepare("INSERT INTO `humidity` (`value`) VALUES (?);")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("i", intval($data))){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la humedad a la base de datos");
  }   
}

function pushAir($conn, $data){
  /* ID and timestamp are added by the database */

  $values = json_decode($data, true);

  $PM10 = $values['PM10'];
  $O3 = $values['O3'];
  $NO2 = $values['NO2'];
  $SO2 = $values['SO2'];

  if(!$ssql = $conn->prepare("INSERT INTO `air` (`PM10`, `O3`, `NO2`, `SO2`) VALUES (?, ?, ?, ?);")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("iiii", $PM10, $O3, $NO2, $SO2)){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la calidad del aire a la base de datos");
  }
}

function getTemperature($conn, $maxValues){

  if(!$ssql = $conn->prepare("SELECT * FROM temperature LIMIT ?;")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la temperatura a la base de datos");
  }

  $res = $ssql->get_result();

  echo json_encode($res->fetch_assoc());
}

function getHumidity($conn, $maxValues){

  if(!$ssql = $conn->prepare("SELECT * FROM humidity LIMIT ?;")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la temperatura a la base de datos");
  }   

  $res = $ssql->get_result();

  echo json_encode($res->fetch_assoc());
}

function getAir($conn, $maxValues){

  if(!$ssql = $conn->prepare("SELECT * FROM air LIMIT ?;")){
    die("Error al preparar la consulta");
  }
  if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
  }
  if(!$ssql->execute()){
      die("Error al añadir la temperatura a la base de datos");
  }

  $res = $ssql->get_result();

  echo json_encode($res->fetch_assoc());
}


/* End Database functions */

$maxValues = 10;

if(isset($_GET['maxvalues'])){
  $maxValues = $_GET['maxvalues'];
}

if(isset($_GET['data'])){
  switch($_GET['data']){
    case 'temperature':
      getTemperature($conn, $maxValues);
      break;
    case 'humidity':
      getHumidity($conn, $maxValues);
      break;
    case 'air':
      getAir($conn, $maxValues);
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