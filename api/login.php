<?php
include 'config.php';
include 'validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(array("success" => false, "message" => "invalid request method."));
  die();
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if($data == null){
  echo json_encode(array("success"=>false,"message"=>"request body is required."));
  die();
}

if($email == null){
  echo json_encode(array("success"=>false,"message"=>"email is required."));
  die();
}
if($password == null){
  echo json_encode(array("success"=>false,"message"=>"password is requied."));
  die();
}

$sql = $conn->prepare("SELECT * FROM users WHERE email = ?");
$sql->bind_param("s",$email);
$sql->execute();

$result= $sql->get_result();
$user = $result->fetch_assoc();
if($user == null){
  echo json_encode(array("success"=>false,"message"=>"user doesnt exist."));
  die();
}
$verify = password_verify($password,$user['password']);

if($user && $verify){
  echo json_encode(array("success"=>true,"message"=>"logged in successfully."));
}
else{
  echo json_encode(array("success"=>false,"message"=>"invalid password."));
}

$sql->close();
$conn->close();
?>