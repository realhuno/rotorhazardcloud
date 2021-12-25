
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
$ergebnis = mysqli_query($conn, "SELECT * FROM laps WHERE lap != '0' and lap='1' or lap='2' or lap='3' group by head order by id desc");
while($row = mysqli_fetch_object($ergebnis))
{
	$headid=$row->head;
	
?>
<div id="container">
    <div class="singleBlock">
        <div class="title">Heat <?php echo $headid; ?> <a href="index.php?page=groups&delete=<?php echo $headid; ?>">[X]</a></div>
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
	$ergebnis11 = mysqli_query($conn, "SELECT * FROM users WHERE id='".$pilotid1."'");
	$username = mysqli_fetch_object($ergebnis11);
	$pilotname=$username->pilotname;

	//anzahl der runden
	$count = mysqli_query($conn,"SELECT COUNT(*) AS anzahl FROM laps WHERE lap != '0' and head='".$headid1."' and pilot_id='".$pilotid1."'");
	$row2=mysqli_fetch_row($count);
	echo "<table width=160px><tr>";


echo "<th align=left>".$pilotname."</th>";
echo "<th align=right>".$row2[0]."/".$sum."</th>";

echo "</tr></table>";	
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
