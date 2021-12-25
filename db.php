<?php

$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$t=$_GET['timestamp'];
$p=$_GET['pilotid'];
$n=$_GET['node'];

$l=$_GET['lap'];
$h=$_GET['head'];


//Get Pilot Name 
$ergebnis11 = mysqli_query($conn, "SELECT * FROM seat WHERE id='".$p."'");
	$username = mysqli_fetch_object($ergebnis11);

	$pilot_id=$username->userid;

$sql = "INSERT INTO laps (timestamp,pilot_id,event_id,lap,head) VALUES (".$t.", ".$pilot_id.", '1',".$l.",".$h.")";



if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();


?>
