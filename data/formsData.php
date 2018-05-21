<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once('../connections/class.connection.php');
require_once('class.data.php');

$config = null;

$con = new Connection($config);
$db_con = $con->getDbCon('nangaien_roadApp');
$data = new Data($db_con);

$action 	  = $_GET['action'];
$action_data  = $_GET['data'];

$country_name = $_GET['country_name'];

if(empty($country_name)){$country_name = 'TANZANIA';}

switch($action){
	case 'getDistrict':
		$ret_data = $data->getDistricts($action_data);
	break;
	
	case 'getWard':
		$ret_data = $data->getWard(json_decode($action_data));
	break;
	
	case 'submitKero':
	    $ret_data = $data->submitKero(json_decode($action_data));
	break;
	
	case 'initIncidentData':
		$ret_data = $data->getIncidentData($action_data);
	break;
	
	case 'initRoadsData':
		$ret_data = $data->getRoadsData($action_data);
	break;
	
	case 'initData':
		$agencies = $data->getAgencies($country_name);
		$region   = $data->getRegions($country_name);
	
		$ret_data = array(
			'agencies' => $agencies,
			'region'   => $region
		);
	break;	
	default:
}#end switch.
echo json_encode($ret_data);
?>
