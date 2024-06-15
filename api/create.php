<?php
include 'config.php';
include 'validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(array("success" => false, "message" => "invalid request method"));
  die();
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;
$password = password_hash($password, PASSWORD_BCRYPT);

if ($data == null) {
  echo json_encode(array("success" => false, "message" => "body is required."));
  die();
}

if ($email == null) {
  echo json_encode(array("success" => false, "message" => "email is required"));
  die();
}
if ($password == null) {
  echo json_encode(array("success" => false, "message" => "password is required."));
  die();
}

$sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
$sql->bind_param("s", $email);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
  echo json_encode(array("success" => false, "message" => "user exists. user another email"));
  die();
}

$insertQuery = $conn->prepare("INSERT INTO users (email,password) VALUES (?,?)");
$insertQuery->bind_param("ss", $email, $password);
$insertResult = $insertQuery->execute();

if ($insertResult) {
  echo json_encode(array("success" => true, "message" => "inserted user successfully."));
} else {
  echo json_encode(array("success" => false, "message" => "failed to insert user"));
}

$insertQuery->close();
$conn->close();

?>