<?php

include 'databaseConfig.php';

// Create connection
$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
 if($_SERVER['REQUEST_METHOD'] == 'POST')
 {
 $DefaultId = 0;
 
 $ImageData = $_POST['image_path'];
 
 $ImageName = $_POST['image_name'];

 $GetOldIdSQL ="SELECT id FROM App_images ORDER BY id ASC";
 
 $Query = mysqli_query($conn,$GetOldIdSQL);
 
 while($row = mysqli_fetch_array($Query)){
	$DefaultId = $row['id'];
 }
 
 $ImagePath = "images/$DefaultId.png";
 $ServerURL = "https://nangaiengineering.com/techboard/androidApp/$ImagePath";
 
 $InsertSQL = "insert into App_images (image_path,image_name) values ('$ServerURL','$ImageName')";
 
 if(mysqli_query($conn, $InsertSQL)){

 file_put_contents($ImagePath,base64_decode($ImageData));

 echo "Your Image Has Been Uploaded.";
 }
 
 mysqli_close($conn);
 }else{
 echo "Not Uploaded";
 }

?>