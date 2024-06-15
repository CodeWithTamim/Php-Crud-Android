<?php
header("Content-Type: application/json");
$access_key = "nasahacker";
$provided_key = $_GET['api_key'] ?? null;

if ($provided_key == null) {
  echo json_encode(array("success" => false, "message" => "api key is required."));
  die();
}

if ($provided_key !== $access_key) {
  echo json_encode(array("success" => false, "message" => "invalid api key."));
  die();
}

?>