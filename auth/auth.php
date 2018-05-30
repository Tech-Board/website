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

$action = $data->params[0]->action;

switch($action){
	case 'checkRegistration':
		$email = $data->params[0]->email;
		$agencyID = $data->params[0]->agencyID;
		
		$agencyExist     = $auth->isAgencyExist($agencyID);
		$emailRegistered = $auth->isEmailRegistered($email);
		
		$actionResponse = array(
			'agencyExist' => $agencyExist,
			'emailRegistered' => $emailRegistered
		);
	break;
	
	case 'resetPassword':
		$email = $data->params[0]->email;
		$emailRegistered = $auth->isEmailRegistered($email);
		
		if($emailRegistered){
			
		  #send email to user.
		  #create the token.
		  $tokenId    = base64_encode(mcrypt_create_iv(32));
		  $issuedAt   = time();
		  $notBefore  = $issuedAt + 10;       //Adding 10 seconds
	      $expire     = $notBefore + 864000;  // Adding one day
		  $serverName = $config['webhost']; // Retrieve the server name from config file
    
		  /*
		   * Create the token as an array
		   */
		  $jwtdata = [
           'iat'  => $issuedAt,         // Issued at: time when the token was generated
		   'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
		   'iss'  => $serverName,       // Issuer
		   'nbf'  => $notBefore,        // Not before
		   'exp'  => $expire,           // Expire
		   'data' => [                  // Data related to the signer user
             'userId' => $email, // userid from the users table
           ]
		 ];	
			
		 $secretKey = base64_decode($config['jwtKey']);	
		 $jwt = JWT::encode(
           $jwtdata,      //Data to be encoded in the JWT
           $secretKey, // The signing key
           'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
         );
		 $email = $auth->send_resetLink($email,$jwt);
			
			$actionResponse = array(
				'isSuccess' => true,
				'message'	=> 'We have sent a password reset link to your email on file. Please click on the link to reset your password.'
			);
		}else{
			$actionResponse = array(
				'isSuccess' => false,
				'message'	=> 'Sorry, email/username not found, please try again with the correct username/email.'
			);
		}
	break;
	
	case 'processRegistration':
		$regResponse = $auth->newRegister($data);
		if($regResponse['isSuccess'] == true){
			#send email to user.
			
			#create the token.
			$tokenId    = base64_encode(mcrypt_create_iv(32));
			$issuedAt   = time();
			$notBefore  = $issuedAt + 10;       //Adding 10 seconds
			$expire     = $notBefore + 864000;  // Adding one day
			$serverName = $config['webhost']; // Retrieve the server name from config file
    
		  /*
		   * Create the token as an array
		   */
		  $jwtdata = [
           'iat'  => $issuedAt,         // Issued at: time when the token was generated
		   'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
		   'iss'  => $serverName,       // Issuer
		   'nbf'  => $notBefore,        // Not before
		   'exp'  => $expire,           // Expire
		   'data' => [                  // Data related to the signer user
             'userId' => $data->params[0]->email, // userid from the users table
           ]
		 ];	
			
		 $secretKey = base64_decode($config['jwtKey']);	
		 $jwt = JWT::encode(
           $jwtdata,      //Data to be encoded in the JWT
           $secretKey, // The signing key
           'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
         );
		 $email = $auth->send_mail($data,$jwt);
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
		$email = $data->params[0]->email;
		$agencyID = $data->params[0]->agencyID;
		$password = $data->params[0]->password;
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