<?php
require_once('../connections/class.connection.php');
require_once('class.auth.php');

#initialize the data connection class. 
$db = new Connection();
$db_users = $db->getUsersCon();
$db_roads = $db->getRoadsCon();
$config   = $db->config;

if (!$db_users || !$db_roads){
  echo json_encode(array('error'=>"Could not connect to the databases"));
  exit;
}

#Initialize the auth class. 
$auth = new Auth($db_users,$db_roads,$config);

$encrypted = $_GET['validation']; 
$password = $config['password'];
$method = $config['emode'];


$key = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
$decrypted = openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);

var_dump($decrypted);

echo 'decrypted to: ' . $decrypted . "\n\n"; echo "<br>";

die();
?>