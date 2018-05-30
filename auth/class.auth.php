<?php
#class containing db connection. 

/*
  @runQuery()    	- executes a query.
  @lastID()   		- return a last insert ID.
  @resister() 		- register new user. 
  @login()    		- to login user. 
  @is_logged_in() 	- returns user session is actvie or not. 
  @logout()   		- to destroy user sessions. 
  @send_mail() 		- to send mail at user registration and send forgot password reset link.   
*/

class Auth
{
	private $conn;
	
	public function __construct($db_users,$db_roads,$config)
	{ 
	  $this->db_users = $db_users;
	  $this->db_roads = $db_roads;
	  $this->config = $config;
	}#end of construct method. 
	
	public function runQuery($sql)
	{
	  $stmt = $this->db_users->prepare($sql);
	  return $stmt;
	}
	
	public function lastID()
	{
		$stmt = $this->db_users->lastInsertId();
		return $stmt;
	}
	
	/*
	 email exists method : method to check if user email already exists. 
	 @par - string : email to check.  
	 @ret - bool : true if email exist, false if it does not. 
	*/
	public function isEmailRegistered($email)
	{
	   $stmt = $this->db_users->prepare("SELECT * FROM techboardUsers WHERE u_email=:email");
       $stmt->execute(array(":email" => $email));
       $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			 
		if($stmt->rowCount() > 0){
			#username exist. 
			return true;
		} else {
			return false; 
		}
	}#end email_exists. 
	
	public function isAgencyExist($agencyID)
	{
	   $stmt = $this->db_roads->prepare("SELECT * FROM tbl_agency WHERE agency_code=:agencyID");
       $stmt->execute(array(":agencyID" => $agencyID));
       $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			 
		if($stmt->rowCount() > 0){
			#agency exists. 
			return true;
		} else {
			return false; 
		}
	}#end isAgencyExist. 
	
	
	public function emailValidate($email){
	   $u_verified = "Y";
	   $stmt = $this->db_users->prepare("UPDATE techboardUsers SET u_verified = :u_verified WHERE u_email=:u_email");
       $stmt->execute(array(
			":u_email" => $email,
			":u_verified" => $u_verified));
			 
		if($stmt){
			return true;
		} else {
			return false; 
		}
	}#emailValidate
	

    /*
     * generateHash : Method that generates hash for the user password.
     * @par - string - password to be hashed.
     * @ret - string - encrypted password.
     */
    public function generateHash($password)
    {
        if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
            $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, 22);
            return crypt($password, $salt);
        }
    }

	
    /*
     * Method to verify user.
     */
    public function verify($password, $hashedPassword) {
        return crypt($password, $hashedPassword) == $hashedPassword;
    }

	
	/*
	 * register : Method to register new users.
	 * @par - user details.
	 * @ret - bool .
	 */
	public function newRegister($data)
    {
		#get sent data.
		$firstName 	  	  = $data->params[0]->firstname;
		$lastName 		  = $data->params[0]->lastname;
		$password 		  = $data->params[0]->password;
		$confirmPassword  = $data->params[0]->confirmPassword;
		$email			  = $data->params[0]->email;
		$agencyCode 	  = $data->params[0]->agencyID;
		$regDateTime 	  = time();
		
		if($firstName == ""){
		   return  array('error'=>'First name is required');	
		}
		
		if($lastName == ""){
		   return  array('error'=>'Last name is required');	
		}
		
		if($password == ""){
		   return  array('error'=>'Password is required');	
		}
		
		if($agencyCode == ""){
		   return  array('error'=>'Agency code is required');	
		}
		
		if($password != $confirmPassword){
			return array('error'=>'Passwords do not match');
		}
				
        $pwd = $this->generateHash($password); #hash the password.

		try
		{ 	 
         $stmt = $this->db_users->prepare("INSERT INTO techboardUsers(u_firstname,u_lastname,agencyCode,u_email,u_password,regDateTime) VALUES(:u_firstname,:u_lastname,:agencyCode,:u_email,:u_password, :regDateTime)");
				
         $stmt->bindparam(":u_firstname",$firstName);
         $stmt->bindparam(":u_lastname",$lastName);
         $stmt->bindparam(":agencyCode",$agencyCode);
	     $stmt->bindparam(":u_email",$email);
		 $stmt->bindparam(":u_password",$pwd);
		 $stmt->bindparam(":regDateTime", $regDateTime);
	     $result = $stmt->execute();
		 
		 
		 if(!$result){
			 $errMessage_1 = 'Failed to execute registration.';
		 } else {
			 return array(
				'isSuccess' => true,
				'message'	=> 'Registration has been successful. We have sent a link to your email for confirmation.'
			 );
		 }
		} catch(PDOException $ex) {
			echo $ex->getMessage();
		}
    } #end register.
	
	
	public function login($email,$agencyID,$password)
    {	
        try
        {
            $stmt = $this->db_users->prepare("SELECT * FROM techboardUsers WHERE u_email=:email");
            $stmt->execute(array(":email" => $email));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            
            #email exist.			
            if($stmt->rowCount() == 1) {
              #check if email is verified
			  if($userRow['u_verified']=="Y"){
				  if($userRow['agencyCode'] == $agencyID){
				  if($userRow['admin_verified']='Y'){
                    $password_in_database  = $userRow['u_password'];
                    $user_entered_password = $password;
                    $status = $this->verify($user_entered_password, $password_in_database);

                    if ($status == true){
					  return array(
						'status' => true, 
						'userId' => $userRow['user_id'],
						'userName' => $userRow['u_email'],
						'firstname' => $userRow['u_firstname'],
						'lastname' => $userRow['u_lastname'],
						'message' => 'Login accepted'
						);
				    } else {
                      #password not found.
                      return array(
					    'status' => false, 
					    'message' => 'Incorrect username or password'
					  );
                      exit;
                    }
				   } else {
					  return array(
					    'status' => false, 
					    'message' => 'Your account has not yet been approved. You will receive an emailed confirmation once this is complete.'
					  );
				   }
				  } else {
					  return array(
					    'status' => false, 
					    'message' => 'Seems like you are trying to log in with an incorrect Agency ID, Please check with your supervisor.'
					  );
				  }
              } else { #user email not verified.
                return array(
					  'status' => false , 
					  'message' => 'Please verify your email address before trying to login.'
					);
                exit;
              }
            } else {
	        #username/email not found.
            return array(
				  'status' => false, 
				  'message' => 'Incorrect username or password'
				); 
              exit;
            }
        }
        catch(PDOException $ex) {
          echo $ex->getMessage();
        }
    }#end public login.
   
   
   public function is_logged_in()
   {
		if(isset($_SESSION['user_id']))
	   {
		return true;
	   }
   } #end is_logged_in. 
 
   public function redirect($url)
   {
    header("Location: $url");
   }
 
   public function logout()
   {
       $user_id = $_SESSION['user_id'];
       $online_status = "offline";

       #remove session from active session.
       try{
           $stmt_0 = $this->conn->prepare("UPDATE chat_users set status = :online_status WHERE user_id = :user_id");
           $stmt_0->execute(array(":user_id" => $user_id, ":online_status" => $online_status));

           $stmt_2 = $this->conn->prepare("insert into closed_sessions (sessionStart,user_id) SELECT sessionStart,user_id from active_sessions WHERE user_id = :user_id");
           $stmt_2->execute(array(":user_id" => $user_id));

           $stmt_3 = $this->conn->prepare("DELETE FROM active_sessions WHERE user_id = :user_id");
           $stmt_3->execute(array(":user_id" => $user_id));
       }catch (PDOException $ex){

       }

       session_destroy();
       $_SESSION['user_id'] = false;
       return true;
   }//end logout.

   public function last_login($user_id)
   {
       try {
           $stmt   = $this->conn->prepare("UPDATE tbl_usernames_passwords SET last_login = CURRENT_TIME WHERE tbl_usernames_passwords.user_id = :user_id");
           $stmt_u = $this->conn->prepare("UPDATE user_profile SET u_last_login = CURRENT_TIME WHERE user_profile.user_id = :user_id");

           $stmt->execute(array(":user_id" => $user_id));
           $stmt_u->execute(array(":user_id" => $user_id));

       } catch (PDOException $ex) {
           echo $ex->getMessage();
       }
   }//end last login.

    /*
     * Set user online status.
     * @par - int : user_id
     * @par - string : online status for chat.
     *
     * @ret - null.
     */
    public function online_status($user_id,$online_status){
        try{
            #update online status on active users and chat users table.
            $stmt = $this->conn->prepare("UPDATE chat_users set status = :online_status WHERE user_id = :user_id");
            $stmt->execute(array(":user_id" => $user_id, ":online_status" => $online_status));

            #active sessions.
            $stmt = $this->conn->prepare("Insert into active_sessions(user_id,active_status) values (:user_id,'1')");
            $stmt->execute(array(":user_id" => $user_id));

        } catch (PDOException $ex){
		    echo $ex->getMessage();
        }
    }//end online status.

	#cv joint 48000  wishibon 65000  bearing 60000

   function send_mail($data,$jwt)
   {      
   	  $firstName  = $data->params[0]->firstname;
	  $lastName   = $data->params[0]->lastname;
	  $email	  = $data->params[0]->email;
	  $agencyCode = $data->params[0]->agencyID;
		
	  $headers="";
	  $headers .= "MIME-Version: 1.0\r\n";
	  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	  $headers .= "From: confirmation@tech-board.com";
		 
		// the message
		$url="http://".$this->config['webhost']."/techboard/auth/validation.php?v=".$jwt;

		$msg="Hi ".$firstName . " <br>,
		 
		";
		$msg.="Please click the following link to confirm your new account. <br>
		 
		";
		$msg.="<a href='".$url."'>Validate my account</a> <br>
		 
		";
		$msg.="Thanks, <br>
		 
		";
		$msg.="The Management Team
		 
		";
		$msg.="&nbsp; <br>
		 
		";
		$msg.="If you have problems with the link above, copy and paste the following URL into a browser <br>
		 
		";
		$msg.="".$url."
		 
		";
		 
		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,80);
		$subject = "Validate account";
		// send email
		return mail($email,$subject,$msg,$headers);
   }#send mail.

   
   
     function send_resetLink($email,$jwt)
   {      
	  $headers="";
	  $headers .= "MIME-Version: 1.0\r\n";
	  $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	  $headers .= "From: confirmation@tech-board.com";
		 
		// the message
		$url="http://".$this->config['webhost']."/techboard/auth/passwordreset.php?v=".$jwt;

		$msg="Hi<br>,
		 
		";
		$msg.="Please click the following link to reset your password. <br>
		 
		";
		$msg.="<a href='".$url."'>Reset my password</a> <br>
		 
		";
		$msg.="Thanks, <br>
		 
		";
		$msg.="The Management Team
		 
		";
		$msg.="&nbsp; <br>
		 
		";
		$msg.="If you have problems with the link above, copy and paste the following URL into a browser <br>
		 
		";
		$msg.="".$url."
		 
		";
		 
		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,80);
		$subject = "Reset password";
		// send email
		return mail($email,$subject,$msg,$headers);
   }#send mail.
//end of USER class.   
}
?>