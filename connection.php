<?php

/* Database connection */

$servername = "localhost";
$username = "root";
$password = "";
$database = "weather";

/*
$servername = "PMYSQL126.dns-servicio.com";
$username = "weatheruser";
$password = "zz811#wO6";
$database = "7439983_weather";
*/

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/* End Database connection */

?>