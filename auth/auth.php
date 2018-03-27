<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$res = array(
	'status' => true
);

echo json_encode($res);


	$userData = array(
		'firstname' 	=> 'John',
		'lastname'  	=> 'Doe',
		'middlename' 	=> 'Penny',
		'birthdate' 	=> '01/12/1992', #dd/mm/yyyy
		'birthplace' 	=> 'Tanga',
		'age' 		 	=> '26',
		'user_gender' 	=> 'male',
		'nationality' 	=> 'Tanzania',
		'user_county_of_residence' => 'Tanzania',
		'user_married' 	=> 'N',
		'user_single'		=> 'N',
		'user_divorced' 	=> 'N',
		'user_separated' 	=> 'N',
		'fathers_name' 	=> 'Charles Doe',
		'fathers_nationality' => 'Tanzania',
		'mothers_name' => 'Theresa Doe',
		'mothers_nationality' => 'Tanzania',
		'user_id_type' => 'N', #for national id.
		'id_issue_agency' => 'NIDA',
		'id_expiration_date' => '15/05/2020',
		'address_region' => 'Dar Es Salaam',
		'address_street' => 'Bonde la mpunga',
		'address_plot_no' => '545',
		'address_slp' => '4565',
		'address_wilaya' =>  'Kinondoni',
		'address_own' => 'N',
		'address_rent' => 'Y',
		'address_extra' => 'NA',
		'user_employed' => 'Y',
		'user_self_employed' => 'N',
		'user_retired'  => 'N',
		'user_work_name'  => 'Wizara ya afya',
		'user_unemployed'  => 'N',
		'user_work_address'  => '456 Posta Street',
		'user_work_phone_no' => '0745656569',
		'registration_date'  => '08/03/2018', #todays date.
		'account_type'  => 'Fixed Savings',
		'account_fast'  => 'N',
		'account_fast_no' => 'NA',
		'account_akiba' => 'N',
		'account_biashara' => 'NA',
		'account_pqa'  => 'N',
		'account_pqa_no'  => 'NA',
		'account_wadu'  => 'Y',
		'account_wadu_no'  => 'NA', #shall be assigned.
		'account_extra'  => 'N',
		'user_picture'  => 'djfskjfhsjfhlsjhfs;ljfeu0qw8u53hsjfvsdbdsfbsdfbde', #blob
		'user_signature'  => 'jfglshflsjhflskfhlsuh vq3985yrisufhlsdb urfv',
		'user_extra_info' => 'Agent notes empty',
		'account_agent_firstname' => 'Charles',
		'account_agent_lastname' => 'Mwaiga',
		'account_agent_notes'=> 'User account being evaluated'
	);

	//echo "<pre>";
	//var_dump($userData);
	//echo json_encode($userData);
	#end script.
?>
