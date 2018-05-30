<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once('../vendor/autoload.php');
use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Http\PhpEnvironment\Request;
use Firebase\JWT\JWT;

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


$token = $_GET['v'];
$secretKey = base64_decode($config['jwtKey']);
$token_get = JWT::decode($token,$secretKey, array('HS512'));

$userId = $token_get->data->userId;

$confirm = $auth->emailValidate($userId);
if($confirm){
	echo "<h2>Email verification successful.</h2>";
} else {
	echo "<h2>Email verification has failed. Please contact us</h2>";
}
?>