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

?>