<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$fullName = $request->fullName;
$password = $request->password;
$email	  = $request->email;

echo json_encode("email is " .$email);

?>