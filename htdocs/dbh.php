<?php

$servername = "ysjcs.net";
$username = "billy.gaines";
$password = "K435RQAG";
$dbName = "billygaines";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbName);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

?> 