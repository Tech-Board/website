<?php
/*
 Class to render database connections. 
*/
class Connection
{
	private $conn;
	public function __construct()
	{
	   $this->config = $config;
	   $this->users_db = 'nangaien_users';
	   $this->roads_db = 'nangaien_roadApp';
	   $this->config   = $this->getDbConfig();
	}#end construct.
	
	
	/*
	  getUsersCon - Method to return db connection for the users database. 
	*/
	public function getUsersCon(){
		return $this->getDbCon($this->users_db);
	}
	
	/*
	  getRoadsCon - Method to return db connection object for roadsApp Db. 
	*/
	public function getRoadsCon(){
		return $this->getDbCon($this->roads_db);
	}
	
	
	 /**
      * @param $hostname
      * @param $uname
      * @param $pwd
      * @param $prefix
      * @return array
      */
    public function getDbCon($db_name){
		
		$host = $this->config['database']['params']['host'];
		$db_user = $this->config['database']['params']['db_user'];
		$db_pass = $this->config['database']['params']['db_pass'];
		
     try { 
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $dbcon = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pass, $options);
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		
        $dbconn = $dbcon;
		return $dbcon;

    #If unsuccessful, return error and kill the script. 
    } catch (PDOException $e) {
       $Error = $e->getMessage();
	   return $Error;
       die();
    } # End catch. 
   }#End of get connection method. 
   
   
   public function db_connecti(){
	// Consumes the configuration array
	$params = $this->config->database->params;
	$conn = mysqli_connect($params->host, $params->username, $params->password,$params->dbname);
 
	/* check connection */
	if (mysqli_connect_errno()) {
	  return false;
	}
	return $conn;
   }#end db_connecti


	public function hash_equals2($str1, $str2){
		if(strlen($str1) != strlen($str2)){
		  return false;
		}else{
		  $res = $str1 ^ $str2;
		  $ret = 0;
		
		for($i = strlen($res) - 1; $i >= 0; $i--){
		  $ret |= ord($res[$i]);
		}
		  return !$ret;
		}
	}#end hash_equals2
 
 
	public function mysqli_result2($res, $row, $field=0) {
		$res->data_seek($row);
		$datarow = $res->fetch_array();
		return $datarow[$field];
	}#end mysqli_result2
	
	
	public function check_input($value,$conn){
		// Stripslashes
		if (get_magic_quotes_gpc()){
			$value = stripslashes($value);
		}
		// Quote if not a number
		if (!is_numeric($value)){
			mysqli_real_escape_string($conn,$value);
		}
		return $value;
	}#end check_input
   
   
   /*
    * @desc : Error handling method.
	* @par  : error array.
    */
   public function errorHandle($err){
	   $e_message = $err['message'] + $err['file'];
	   
	   $email = array(
			'subject'  => 'OK-Roads Error reporting',
			'email'    => 'magesa@ou.edu',
			'message'  => $e_message,
			'fromName' => 'Roads Oklahoma error report'
	   );
	   $this->send_mail($email);
   }#end errorHandle.
   
   
   public function getDbConfig(){
	   $configArray = array(
			'webhost'  => 'nangaiengineering.com',
			'password' => '3sc3RLrpd17',
			'emode'    => 'aes-256-cbc',
			'jwtKey'   => 'LSbLKHoJ3iJ9dBP6tnLU/+q9oCooe2rYKybsVGMBNpPZgGOcJtsuBFHZ7eAjvHF+8/Q38Nud/e0g98xPignOeg==',
			'database' => array(
			'adapter' => 'pdo_mysql',
			'params'  => array(
				'host'    => '50.116.103.93',
				'db_user' => 'nangaien_dmagesa',
				'db_pass' => 'DanWins8942#'
				)
			)
		);
		return $configArray;
   }#end getDbConfig.
   
   
   /*
    * @desc : send email. 
	* @par  : email array.
	* @ret  : email send result. 
	*
    */
   public function send_mail($e){
    require_once('class.phpmailer.php');
	require_once('class.smtp.php');
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->headers = "MIME-Version: 1.0" . "\r\n";
    $mail->headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    #$mail->SMTPDebug = 0;                     
    $mail->SMTPAuth   = false;                                  
    $mail->Host       = "smtp-relay.gmail.com";
    #$mail->Port      = 465;             
    $mail->From       = 'no-reply@okroads.org';
    $mail->FromName   = $e['fromName']; #Roads oklahoma error report.
    $mail->AddAddress($e['email']);          
    $mail->Subject    = $e['subject'];
    $mail->MsgHTML($e['message']);
    $mail->Send();
   }//end send mail.
 } # End of overhead class
 

//end of Connection class.
?>