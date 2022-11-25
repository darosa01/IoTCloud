<?php

function pushTemperature($conn, $data, $timestamp = null){
  if($timestamp != null){
    if(!$ssql = $conn->prepare("INSERT INTO `temperature` (`value`,`datetime`) VALUES (?,?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("is", $data, $timestamp)){
      return("Error al vincular los parametros");
    }
  } else {
    /* ID and timestamp are added by the database */
    if(!$ssql = $conn->prepare("INSERT INTO `temperature` (`value`) VALUES (?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("i", $data)){
      return("Error al vincular los parametros");
    }
  }

  if(!$ssql->execute()){
    return("Error al añadir la humedad a la base de datos");
  }

  return 'Temperature data has been stored! ';
}

function pushHumidity($conn, $data, $timestamp = null){
  if($timestamp != null){
    if(!$ssql = $conn->prepare("INSERT INTO `humidity` (`value`,`datetime`) VALUES (?,?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("is", $data, $timestamp)){
      return("Error al vincular los parametros");
    }
  } else {
    /* ID and timestamp are added by the database */
    if(!$ssql = $conn->prepare("INSERT INTO `humidity` (`value`) VALUES (?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("i", $data)){
      return("Error al vincular los parametros");
    }
  }
  
  if(!$ssql->execute()){
    return("Error al añadir la humedad a la base de datos");
  }

  return 'Humidity data has been stored! ';
}

function pushAir($conn, $data, $timestamp = null){

  $values = $data;

  if(gettype($data) == "string"){
    $values = json_decode($data, true);
  }
  
  $PM10 = $values['PM10'];
  $O3 = $values['O3'];
  $NO2 = $values['NO2'];
  $SO2 = $values['SO2'];

  if($timestamp != null){
    if(!$ssql = $conn->prepare("INSERT INTO `air` (`datetime`,`PM10`, `O3`, `NO2`, `SO2`) VALUES (?, ?, ?, ?, ?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("siiii", $timestamp, $PM10, $O3, $NO2, $SO2)){
      return("Error al vincular los parametros");
    }
  } else {
    /* ID and timestamp are added by the database */
    if(!$ssql = $conn->prepare("INSERT INTO `air` (`PM10`, `O3`, `NO2`, `SO2`) VALUES (?, ?, ?, ?);")){
      return("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("iiii", $PM10, $O3, $NO2, $SO2)){
      return("Error al vincular los parametros");
    }
  }
  
  if(!$ssql->execute()){
    return("Error al añadir la calidad del aire a la base de datos");
  }

  return 'Air quality data has been stored! ';
}

function getTemperature($conn, $maxValues = -1){

  if($maxValues == -1){
    if(!$ssql = $conn->prepare("SELECT * FROM temperature;")){
      die("Error al preparar la consulta");
    }
  } else {
    if(!$ssql = $conn->prepare("SELECT * FROM temperature LIMIT ?;")){
      die("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
    }
  }
  
  if(!$ssql->execute()){
    die("Error al obtener los datos de temperatura");
  }

  $res = $ssql->get_result();

  $emparray = array();
  while($row = $res->fetch_assoc()){
    $emparray[] = $row;
  }

  return json_encode($emparray);
}

function getHumidity($conn, $maxValues = -1){
  if($maxValues == -1){
    if(!$ssql = $conn->prepare("SELECT * FROM humidity;")){
      die("Error al preparar la consulta");
    }
  } else {
    if(!$ssql = $conn->prepare("SELECT * FROM humidity LIMIT ?;")){
      die("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
    }
  }
  
  if(!$ssql->execute()){
    die("Error al obtener los datos de humedad");
  }   

  $res = $ssql->get_result();

  $emparray = array();
  while($row = $res->fetch_assoc()){
    $emparray[] = $row;
  }

  return json_encode($emparray);
}

function getAir($conn, $maxValues = -1){

  if($maxValues == -1){
    if(!$ssql = $conn->prepare("SELECT * FROM air;")){
      die("Error al preparar la consulta");
    }
  } else {
    if(!$ssql = $conn->prepare("SELECT * FROM air LIMIT ?;")){
      die("Error al preparar la consulta");
    }
    if(!$ssql->bind_param("i", $maxValues)){
      die("Error al vincular los parametros");
    }
  }
  
  if(!$ssql->execute()){
    die("Error al obtener los datos de calidad del aire");
  }

  $res = $ssql->get_result();

  $emparray = array();
  while($row = $res->fetch_assoc()){
    $emparray[] = $row;
  }

  return json_encode($emparray);
}

?>