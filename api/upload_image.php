<?php
include 'config.php';
include 'validate.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(array("success" => false, "message" => "invalid request method."));
  die();
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$name = $data['name'] ?? null;
$image = $data['image'] ?? null;

if ($image == null || $name == null) {
  echo json_encode(array("success" => true, "message" => "all contents are required."));
  die();
}

$target_dir = "uploads/";

/**
 * generate a unique filename
 * for the image that we uploaded.
 */

$image_name = rand() . "_" . time() . ".jpeg";

//complete path for the new image

$target_upload_dir = $target_dir . $image_name;

//decode the base64 image and upload it to the server.
file_put_contents($target_upload_dir, base64_decode($image));

//create sql query and insert the path and the name
$sql = $conn->prepare("INSERT INTO images (image_name,image_path) VALUES (?,?)");
$sql->bind_param("ss", $image_name, $target_upload_dir);
$result = $sql->execute();

if ($result) {
  echo json_encode(array("success" => true, "message" => "image uploaded successfully."));
} else {
  echo json_encode(array("success" => false, "message" => "failed to  upload image."));
}

?>