<?php

require_once "connection.php";

if(!$ssql = $conn->prepare("TRUNCATE `air`; ")){
  die("Error al preparar la consulta");
}
if(!$ssql->execute()){
  die("Error al eliminar los datos de calidad del aire");
}

if(!$ssql = $conn->prepare("TRUNCATE `humidity`; ")){
  die("Error al preparar la consulta");
}
if(!$ssql->execute()){
  die("Error al eliminar los datos de humedad");
}

if(!$ssql = $conn->prepare("TRUNCATE `temperature`; ")){
  die("Error al preparar la consulta");
}
if(!$ssql->execute()){
  die("Error al eliminar los datos de temperatura");
}

$conn->close();

header("Location: ".dirname($_SERVER['REQUEST_URI']));

?>