<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

echo json_encode($data);
die();
?>
