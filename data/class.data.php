<?php

class Data
{
	private $conn;
	public function __construct($conn)
	{ 
	  $this->conn = $conn;
	}#end of construct method. 
	
	public function runQuery($sql)
	{
	  $stmt = $this->conn->prepare($sql);
	  return $stmt;
	}
	
	public function lastID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	/*
	  Get System registered agencies.
	*/
	public function getAgencies($country){
		$stmt = $this->conn->prepare("SELECT * FROM tbl_agency where country_name = :country_name");
		$stmt->execute(array(':country_name' => $country));
		$agencies = $stmt->fetchAll();
		return $agencies;
	}#getAgencies
	
	
	public function getIncidentData(){
		$stmt = $this->conn->prepare("SELECT * FROM tbl_rawKero limit 15");
		$stmt->execute();
		$i=0;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[$i] = array(
                'id'            => $row['id'],
                'country_name'  => $row['country_name'],
                'region_name'   => $row['region_name'],
                'district_name' => $row['district_name'],
                'ward_name'     => $row['ward_name'],
                'agency_name' 	=> $row['agency_name'],
                'agency_code'   => $row['agency_code'],
                'maelezo'      	=> $row['maelezo'],
                'latitude' 		=> (float)$row['latitude'],
                'longitude' 	=> (float)$row['longitude']
            );
            $i++;
        }
		return $data;
	}#end getIncidentData


	#get troop names.
	function getTroop(){

		$sql = "Select * from roads_troop order by troop_char";
		$checked = false;

		#try catch here.
		try {
			$sth = $this->conn->prepare($sql);
			$isQueryOk = $sth->execute();

			$i = 0;
			while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {

				$troops[$i] = array(
					'id'                 => 'troop',
					'troop_char_code'    => $row['troop_char'],
					'name'      		 => $row['troop_name'],
					'checked' 			 => ($row['checked'] == "1") ? true : $checked,
					'code'      		 => $row['troop'],
					'char_code' 		 => $row['troop_char'],
					'comments'       	 => (int)$row['comments']
				);
				$i++;
			}
			return $troops;

		} catch (PDOException $e) {
			$error = $e->getMessage();
			echo $error;
			die();
		}
	}#end troop.



	/*
	 * @par dbh   : obj    - dabatase connection.
	 * @par loc   : string - location (county_name, div_no, hwy_name, troop_no etc);
	 * @par level : int    - level of user.
	 *
	 */
	function getDiv(){
		$sql = "select * from roads_division order by div_no";
		$checked = false;
		
		try {
			$sth = $this->conn->prepare($sql);
			$isQueryOk = $sth->execute();

			$row = array();
			if ($isQueryOk) {
				$i = 0;
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
					$div[] = array(
						'id'                 => 'div',
						'div_no'    		 => $row['div_no'],
						'name'               => $row['div_no'],
						'checked' 			 => ($row['checked'] == "1") ? true : $checked,
						'comments'       	 => (int)$row['comments']
					);
					$i++;
				}
				return $div;
			}
		} catch (PDOException $e) {
			$Error = $e->getMessage();
			echo $Error;
			die();
		}
	}#end getdiv.


	//get entire division array. counties, highways, and control sections.
	function getCounty(){

		$sql = "select * from roads_county";    

		try {
			$sth = $this->conn->prepare($sql);
			$isQueryOk = $sth->execute();

			$row = array();
			if ($isQueryOk) {
				$i = 0;
				while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
					$counties[] = array(
						'id'				 => 'county',
						'div_no'    		 => $row['div_no'],
						'name'				 => $row['co_name'],
						'co_name' 			 => $row['co_name'],
						'co_code'  			 => $row['co_code'],
						'checked'			 => $row['checked'],
						'displayHwys' 		 => ($row['checked'] == "1") ? true : false,
						'troop_no'  		 => $row['troop_char'],
						'slickInSpotsC'      => ($row['slickInSpotsC'] == "1") ? true : false,
						'slickInSpotsH'      => ($row['slickInSpotsH'] == "1") ? true : false,
						'slickAndHarzadousC' => ($row['slickAndHarzadousC'] == "1") ? true : false,
						'slickAndHarzadousH' => ($row['slickAndHarzadousH'] == "1") ? true : false,
						'closed'   		     => ($row['closed'] == "1") ? true : false,
						'lightSnow'			 => ($row['lightSnow'] == "1") ? true : false,
						'moderateSnow'		 => ($row['moderateSnow'] == "1") ? true : false,
						'heavySnow' 		 => ($row['heavySnow'] == "1") ? true : false,
						'snowPacked'  		 => ($row['snowPacked'] == "1") ? true : false,
						'blowingSnow' 		 => ($row['blowingSnow'] == "1") ? true : false,
						'fog'		         => ($row['fog'] == "1") ? true : false,
						'sleetOrIce'    	 => ($row['sleetOrIce'] == "1") ? true : false,
						'flooded'   		 => ($row['flooded'] == "1") ? true : false,
						'stormDamage'    	 => ($row['stormDamage'] == "1") ? true : false,
						'comments'       	 => (int)$row['comments']
					);
					$i++;
				}

				return $counties;
			}
		} catch (PDOException $e) {
			$error = $e->getMessage();
			echo $error;
			die();
		}
	}#end getCounty.



	#get highway names for each county.
	function getHighway(){

		$sql = "select distinct * from roads_highway order by hwy_name";

		try{

			$stmt = $this->conn->prepare($sql);
			$stmt->execute();
			$i=0;
			while($row = $stmt->fetch()){

				$hwyNames[] = array(
					'id'					=> 'hwy',
					'name'				 	=> $row['road_id'],
					'div_no'    		    => $row['div_no'],
					'hwy_name' 				=> $row['hwy_name'],
					'checked'			 	=> $row['checked'],
					'co_name' 				=> $row['co_name'],
					'co_code'  				=> $row['co_code'],
					'slickInSpotsC'         => ($row['slickInSpotsC'] == "1") ? true : false,
					'slickInSpotsH'         => ($row['slickInSpotsH'] == "1") ? true : false,
					'slickAndHarzadousC'    => ($row['slickAndHarzadousC'] == "1") ? true : false,
					'slickAndHarzadousH'    => ($row['slickAndHarzadousH'] == "1") ? true : false,
					'closed'   		     	=> ($row['closed'] == "1") ? true : false,
					'lightSnow'			 	=> ($row['lightSnow'] == "1") ? true : false,
					'moderateSnow'		 	=> ($row['moderateSnow'] == "1") ? true : false,
					'heavySnow' 		 	=> ($row['heavySnow'] == "1") ? true : false,
					'snowPacked'  		 	=> ($row['snowPacked'] == "1") ? true : false,
					'blowingSnow' 		 	=> ($row['blowingSnow'] == "1") ? true : false,
					'fog'		 	        => ($row['fog'] == "1") ? true : false,
					'sleetOrIce'    	 	=> ($row['sleetOrIce'] == "1") ? true : false,
					'flooded'   		 	=> ($row['flooded'] == "1") ? true : false,
					'stormDamage'    	 	=> ($row['stormDamage'] == "1") ? true : false,
					'comments'       	    => (int)$row['comments']
				);
				$i++;
			}
			return $hwyNames;
		} catch (PDOException $e) {
			$error = $e->getMessage();
			echo $error;
			die();
		}
	}#end getHighway.
		
	
	public function getRoadsData(){			
		
		$div      = $this->getDiv($inc_link,$access);
		$county   = $this->getCounty($inc_link,$access);
		$highways = $this->getHighway($inc_link,$access);
		
		$data = array(
			'div'        => $div,
			'county'     => $county,
			'highway'    => $highways
		);
		return $data;
	}#end getIncidentData

	
	/*
	  Get regions.
	*/
	public function getRegions($country){
		$stmt = $this->conn->prepare("SELECT * FROM tbl_regions where country_name = :country_name");
		$stmt->execute(array(':country_name' => $country));
		$regions = $stmt->fetchAll();
		return $regions;
	}#get districts
	
	
	/*
	  Get districts.   
	*/
	public function getDistricts($region){
		$stmt = $this->conn->prepare("SELECT * FROM tbl_districts where region_name = :region_name");
		$stmt->execute(array(':region_name' => $region));
		$districts = $stmt->fetchAll();
		return $districts;
	}#get districts
	
	
	public function getWard($data){
		$region = $data[0];
		$district = $data[1];
		
		$stmt = $this->conn->prepare("SELECT * FROM tbl_wards where region_name = :region_name AND district_name = :district_name");
		$stmt->execute(array(':region_name' => $region,':district_name' => $district));
		$wards = $stmt->fetchAll();
		return $wards;
	}#end getWard.
	
	
	public function getAgencyCode($name){
		$stmt = $this->conn->prepare("SELECT agency_code FROM tbl_agency where agency_name = :name");
		$stmt->execute(array(':name' => $name));
		$name = $stmt->fetchAll();
		return $name[0]['agency_code'];
	}#end getAgencyCode.
	
	
		
	public function getCountryName(){
		return 'TANZANIA';
	}#end getCountryName.
	
	
	public function getCountryCode(){
		return '255';
	}#end getCountryCode.
	
	/*
	  submit Kero. 
	*/
	public function submitKero($data){
		$agency_name = $data[0];
		$region    = $data[1];
		$district  = $data[2];
		$ward      = $data[3];
		$maelezo   = $data[4];
		$latitude  = $data[5];
		$longitude = $data[6];
		$agency_code  = $this->getAgencyCode($agency_name);
		$country_name = $this->getCountryName();
		$country_code = $this->getCountryCode();
		try{
		  $stmt = $this->conn->prepare("INSERT INTO tbl_rawKero (country_name, country_code, region_name, district_name, ward_name,agency_name,agency_code, maelezo,latitude,longitude) VALUES (:country_name, :country_code, :region_name, :district_name, :ward_name, :agency_name, :agency_code, :maelezo,:latitude,:longitude)");
		  $success = $stmt->execute(array(
				':country_name'  => $country_name,
				':country_code'  => $country_code,
				':region_name'   => $region,
				':district_name' => $district,
				':ward_name'     => $ward,
				':agency_name'   => $agency_name,
				':agency_code'   => $agency_code,
				':maelezo'	     => $maelezo,	
				':latitude'      => $latitude,
				':longitude'     => $longitude
				));
		} catch(PDOException $ex) {
		   return $ex->getMessage();
		}
		return $success;
	}#end submit kero.
	
	
	/*
	 email exists method : method to check if user email already exists. 
	 @par - string : email to check.  
	 @ret - bool : true if email exist, false if it does not. 
	*/
	public function email_exists($email)
	{
	   $stmt = $this->conn->prepare("SELECT * FROM tbl_usernames_passwords WHERE userEmail=:email");
            $stmt->execute(array(":email" => $email));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			 
            if($stmt->rowCount() > 0){
				#username exist. 
				return true;
			} else {
				return false; 
			}
	}#end email_exists. 
	

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
		$firstName 	  	  = $data->firstName;
		$lastName 		  = $data->lastName;
		$password 		  = $data->password;
		$confirmPassword  = $data->confirmPassword;
		$email			  = $data->email;
		$agencyCode 	  = $data->agencycode;
		$user_id          = 'reorkjsjfnksj';
		
		
		if($firstName == ""){
		   return  array('error'=>'First name is required');	
		}
		
		if($lastName == ""){
		   return  array('error'=>'Last name is required');	
		}
		
		if($password == ""){
		   return  array('error'=>'Password is required');	
		}
		
		try
		{ 

			// A higher "cost" is more secure but consumes more processing power
			$cost = $this->config->cost;
			// Create a random salt
			$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
			// Prefix information about the hash so PHP knows how to verify it later.
			// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
			$salt = sprintf("$2a$%02d$", $cost) . $salt;
			// Hash the password with the salt
			$passwordHash = crypt($password, $salt);
			//Lets add the user to the DB
		 
		 
		 
		 $query = "insert into techDashUsers (user_id,u_firstname,u_lastname,agencyCode,u_email,u_password) values
					('$user_id','$firstName','$lastName','$agencyCode','$email','$passwordHash');";
		  $result = mysqli_query($this->conn,$query);
		 
		 
         #$stmt = $this->conn->prepare("INSERT INTO techDashUsers(user_id,u_firstname,u_lastname,agencyCode,u_email,u_password) VALUES(:user_id,:u_firstname,:u_lastname,:agencyCode,:u_email,:u_password)");
				
         #$stmt->bindparam(":user_id",$user_id);
         #$stmt->bindparam(":u_firstname",$firstName);
         #$stmt->bindparam(":u_lastname",$lastName);
         #$stmt->bindparam(":agencyCode",$agencyCode);
	     #$stmt->bindparam(":u_email",$email);
		 #$stmt->bindparam(":u_password",$passwordHash);
	     #$res = $stmt->execute();
		 
		 if(!$result){
			 return array('error'=>'Failed to execute registration.');
		 } else {
			 return $this->send_mail($email);
		 }
     }
     catch(PDOException $ex)
     {
      echo $ex->getMessage();
     }
    } #end register.
	
	
	public function login($email,$upass)
    {	
        try
        {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_usernames_passwords WHERE tbl_usernames_passwords.userEmail=:email");
            $stmt->execute(array(":email" => $email));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            
            #email exist.			
            if($stmt->rowCount() == 1) {
              #check if email is verified. 
			  if($userRow['emailVerification']=="Y") {
                  $password_in_database  = $userRow['userPass'];
                  $user_entered_password = $upass;
                  $status = $this->verify($user_entered_password, $password_in_database);

                  if ($status == true){
                      
					  #get user profile information. 
					  $stmt_get_profile = $this->conn->prepare("Select * FROM user_profile WHERE user_id = :user_id");
                      $stmt_get_profile->execute(array(":user_id" => $userRow['user_id']));
                      $userProfile=$stmt_get_profile->fetch(PDO::FETCH_ASSOC);
					  
					  $sp_Approval = $userProfile['sp_Approval'];
					  $adApproval = $userProfile['adApproval'];
					   
					  if(($sp_Approval == 'N') || ($adApproval == 'N')){
						   return array('status' => false, 'message' => 'Your account is still under review, please try again later.');
					  } else {
						  #user has been cleared to login. 
						  $_SESSION['organization']				  = $userProfile['us_organization'];
						  $_SESSION['isAdmin']					  = $userProfile['isAdmin'];
						  $_SESSION['isSupv']					  = $userProfile['isSupv'];
						  $_SESSION['phone_no']					  = $userProfile['u_phone'];
						  $_SESSION['sp_Approval']                = $userProfile['sp_Approval'];
						  $_SESSION['ad_Approval']                = $userProfile['adApproval'];
						  $_SESSION['suspended']                  = $userProfile['suspended'];
						  $_SESSION['blocked']                    = $userProfile['blocked'];
						  $_SESSION['isReadOnly']				  = $userProfile['isReadOnly'];						  
						  $_SESSION['user_id']                    = $userRow['user_id'];
						  $_SESSION['firstname']                  = $userRow['firstname'];
						  $_SESSION['lastname']                   = $userRow['lastname'];
						  $_SESSION['profile_complete_status']    = $userRow['profile_complete_status'];
						  
						  #update login status.
						  $this->online_status($userRow['user_id'],'online');
                          $this->last_login($userRow['user_id']);
					      return array('status' => true, 'message' => 'Login accepted');
					  }
                } else {
                  #password not found.
                  return array('status' => false, 'message' => 'Incorrect username or password');
                  exit;
                }
              } else {
	            #user inactive. either email not verified or has been limited access by admin.
                return array('status' => false , 'message' => 'Please verify your email address before trying to login.');
                exit;
              }
            } else {
	        #username/email not found.
            return array('status' => false, 'message' => 'Incorrect username or password'); 
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

   function send_mail($email)
   {      
      $message = "Hi ". $email ." Please click here to confirm your email address.";
      $subject = "IMS system email confirmation.";
      
	   $encrypted = openssl_encrypt("username|".$email."|email|".$email,$this->config->emode,$this->config->key) ;
		$headers="";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		 
		// the message
		$url="http://".$this->config->webhost."/sco/validation.html?validation=".$encrypted;
		$msg="Hi ".$email . ",
		 
		";
		$msg.="Please click the following link to confirm your new account.
		 
		";
		$msg.="<a href='".$url."'>Validate my account</a>
		 
		";
		$msg.="Thanks,
		 
		";
		$msg.="The Management Team
		 
		";
		$msg.="&nbsp;
		 
		";
		$msg.="If you have problems with the link above, copy and paste the following URL into a browser
		 
		";
		$msg.="".$url."
		 
		";
		 
		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,80);
		// send email
		mail($email,"Please validate your account",$msg,$headers);
		return array('success' => 'true');
   }#send mail.

//end of USER class.   
}
?>