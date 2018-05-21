<?php
require_once('../vendor/autoload.php');
use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Http\PhpEnvironment\Request;
use Firebase\JWT\JWT;
function db_connecti()
{
// Consumes the configuration array
$config = new Zend\Config\Config(include '../config.php');
$conn = mysqli_connect($config->db_server, $config->db_user, $config->db_pass,$config->db_name);
 
/* check connection */
if (mysqli_connect_errno()) {
return false;
}
return $conn;
}
function hash_equals2($str1, $str2)
{
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
}
 
 
function mysqli_result2($res, $row, $field=0) {
$res->data_seek($row);
$datarow = $res->fetch_array();
return $datarow[$field];
}
 
function check_input($value,$conn)
{
// Stripslashes
if (get_magic_quotes_gpc()){
$value = stripslashes($value);
}
// Quote if not a number
if (!is_numeric($value)){
mysqli_real_escape_string($conn,$value);
}
return $value;
}
?>