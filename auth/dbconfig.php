<?php


// An array of configuration data is given
$configArray = array(
    'webhost'  => 'www.nangaiengineering.com',
    'database' => array(
        'adapter' => 'pdo_mysql',
        'params'  => array(
            'host'     => '50.116.103.93',
            'username' => 'nangaien_dmagesa',
            'password' => 'DanWins8942#',
            'dbname'   => 'nangaien_users'
        )
    )
);


/*
	$servername = "50.116.103.93";
	$username = "nangaien_dmagesa";
	$password = "DanWins8942#";
	$db_name  = "nangaien_users";
	
	
	
	try {
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $dbcon = new PDO("mysql:host=$servername;dbname=$db_name", $username, $password, $options);
        $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        $dbcon->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		
        $dbconn = $dbcon;	
	    var_dump($dbconn);
		
		}
			catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
		
		*/
?>