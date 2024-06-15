<?php
include 'config.php';
include 'validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  echo json_encode(array("success" => false, "message" => "invalid request method."));
  die();
}


$sql = $conn->prepare("SELECT * FROM users");
$sql->execute();
$result = $sql->get_result();
$users = array();

while ($row = $result->fetch_assoc()) {
  $users[] = $row;
}
echo json_encode($users);

?>