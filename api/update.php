<?php
include 'config.php';
include 'validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(array("success" => false, "message" => "invalid request method."));
  die();
}
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$user_id = $data['id'] ?? null;
$password = $data['password'] ?? null;
$password = password_hash($password, PASSWORD_BCRYPT);

if ($user_id == null || $password == null) {
  echo json_encode(array("success" => false, "message" => "missing required body contents."));
  die();
}

if (!is_numeric($user_id)) {
  echo json_encode(array("success" => false, "message" => 'id must me a integer value.'));
  die();
}


$sql = $conn->prepare("SELECT * FROM users WHERE id = ?");
$sql->bind_param("i", $user_id);
$sql->execute();
$result = $sql->get_result();
$user = $result->fetch_assoc();

if (!$result->num_rows > 0) {
  echo json_encode(array("success" => false, "message" => "user doesnt existst"));
  die();
}

$updateQuery = $conn->prepare("UPDATE users SET password = ? WHERE id = ? ");
$updateQuery->bind_param("si", $password, $user_id);
$updateResult = $updateQuery->execute();

if ($updateQuery) {
  echo json_encode(array("success" => true, "message" => "password updated successfully."));
} else {
  echo json_encode(array("success" => false, "message" => "failed to update passsword"));
}

$updateQuery->close();
$sql->close();
$conn->close();

?>