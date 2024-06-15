<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "api_development";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
  echo json_encode(array("successs" => false, "message" => "invalid details"));
  die();
}


?>