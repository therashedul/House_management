<?php 
$conn = new mysqli("localhost","root","","house");

// Check connection
if ($conn->connect_errno) {
	echo "Failed to connect to MySQL: " . $conn->connect_error;
	exit();
}
date_default_timezone_set("Asia/Dhaka");

// $conn= new mysqli('localhost','root','','house')or die("Could not connect to mysql".mysqli_error($conn));
