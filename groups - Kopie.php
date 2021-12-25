<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

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


//GROUP by Heads
$ergebnis = mysqli_query($conn, "SELECT * FROM laps WHERE lap != '0' and lap='1' or lap='2' or lap='3' group by head");
while($row = mysqli_fetch_object($ergebnis))
{
	$headid=$row->head;
	
?>
<div id="container">
    <div class="singleBlock">
        <div class="title">Head <?php echo $headid; ?></div>
        <table  class="listitems2">   
<?php
	
  echo " <tr>";
echo "<td>";
$ergebnis1 = mysqli_query($conn, "SELECT * FROM laps WHERE head='".$headid."' group by pilot_id");
while($row1 = mysqli_fetch_object($ergebnis1))
{
		$headid1=$row1->head;
	$pilotid1=$row1->pilot_id;

		$res = mysqli_query($conn,"SELECT sum(timestamp) FROM laps WHERE lap != '0' and head='".$headid1."' and pilot_id='".$pilotid1."'");
if (FALSE === $res) die("Select sum failed: ".mysqli_error);
$row = mysqli_fetch_row($res);

	
$sum = $row[0]/1000;
	
	

	echo "Node(".$pilotid1.")";
		echo " ";
	echo $sum;
	echo "<br>";
	echo "<br>";
	
}

echo "</td>";
echo " </tr>";
 ?>
</table>
</div>
<?php 
}



 

$conn->close();


?>
