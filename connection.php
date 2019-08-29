<?php
	$conn=mysqli_connect('localhost','root','','gpsmap');
	if($conn){
		echo "connected successfully";
	}
	else{
		echo $conn->error;
	}
?>