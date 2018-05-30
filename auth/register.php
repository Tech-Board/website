<?php 

$ch = curl_init('https://www.instagram.com/mangekimambi/media');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
echo $response;


die();








































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
		
		$actionResponse = array(
			'agencyExist' => $agencyExist,
			'emailRegistered' => $emailRegistered
		);
	break;
	
	case 'processRegistration':
		$regResponse = $auth->newRegister($data);
		if($regResponse['isSuccess'] == true){
			#send email to user.
			
			#create the token.
			$tokenId    = base64_encode(mcrypt_create_iv(32));
			$issuedAt   = time();
			$notBefore  = $issuedAt + 10;             //Adding 10 seconds
			$expire     = $notBefore + 60;            // Adding 60 seconds
			$serverName = $config['webhost']; // Retrieve the server name from config file
    
		  /*
		   * Create the token as an array
		   */
		  $data = [
           'iat'  => $issuedAt,         // Issued at: time when the token was generated
		   'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
		   'iss'  => $serverName,       // Issuer
		   'nbf'  => $notBefore,        // Not before
		   'exp'  => $expire,           // Expire
		   'data' => [                  // Data related to the signer user
             'userId' => $login['userId'], // userid from the users table
           ]
		 ];	
			
		 $secretKey = base64_decode($config['jwtKey']);	
		 $jwt = JWT::encode(
           $data,      //Data to be encoded in the JWT
           $secretKey, // The signing key
           'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
         );
		 $email = $auth->sendEmail($data,$jwt);	
		 if($email){
			 $actionResponse = $regResponse;
		 } else {
			 $actionResponse = array(
				'isSuccess' => false,
				'message'	=> 'Something went wrong, Please try again later.'
			 );
		 }
		}
	break;
	#
	case 'login':
		$email = $data->params->email;
		$agencyID = $data->params->agencyID;
		$password = $data->params->password;
		$login = $auth->login($email,$agencyID,$password);
		if($login['status']){
		  #create the token.
		  $tokenId    = base64_encode(mcrypt_create_iv(32));
		  $issuedAt   = time();
		  $notBefore  = $issuedAt + 10;             //Adding 10 seconds
		  $expire     = $notBefore + 60;            // Adding 60 seconds
		  $serverName = $config['webhost']; // Retrieve the server name from config file
    
		  /*
		   * Create the token as an array
		   */
		  $data = [
           'iat'  => $issuedAt,         // Issued at: time when the token was generated
		   'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
		   'iss'  => $serverName,       // Issuer
		   'nbf'  => $notBefore,        // Not before
		   'exp'  => $expire,           // Expire
		   'data' => [                  // Data related to the signer user
             'userId'   => $login['userId'], // userid from the users table
             'userName' => $login['userName'], // User name
           ]
		 ];	
			
		 $secretKey = base64_decode($config['jwtKey']);	
		 $jwt = JWT::encode(
           $data,      //Data to be encoded in the JWT
           $secretKey, // The signing key
           'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
         );	
		 $actionResponse = array(
				'firstname' => $login['firstname'],
				'lastname' => $login['lastname'],
				'isSuccess' => true,
				'message' => 'Login is successful',
				'jwt' => $jwt
			);	
		} else {
			$actionResponse = $login;
		}
	break;
}
echo json_encode($actionResponse);
?>