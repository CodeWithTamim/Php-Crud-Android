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



$deleteQuery = $conn->prepare("DELETE FROM users WHERE id = ? ");
$deleteQuery->bind_param("i", $user_id);
$deleteResult = $deleteQuery->execute();

if ($deleteResult) {
  echo json_encode(array("success" => true, "message" => "user deleted successfully."));
} else {
  echo json_encode(array("success" => false, "message" => "failed to delete user."));
}

$deleteQuery->close();
$sql->close();
$conn->close();




?>