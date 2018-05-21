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
$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$action = $data->params->action;
switch($action){
	case 'checkRegistration':
		$email = $data->params->email;
		$agencyID = $data->params->agencyID;
		
		$agencyExist     = $auth->isAgencyExist($agencyID);
		$emailRegistered = $auth->isEmailRegistered($email);
		
		$register = array(
			'agencyExist' => $agencyExist,
			'emailRegistered' => $emailRegistered
		);
	break;
	
	case 'processRegistration':
		$register = $auth->newRegister($data);
	break;
	#
	case 'login':
		$email = $data->params->email;
		$agencyID = $data->params->agencyID;
		$password = $data->params->password;
		$register = $auth->login($email,$agencyID,$password);
	break;
}
echo json_encode($register);
?>