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
$ergebnis = mysqli_query($conn, "SELECT * FROM users order by id desc");



while($row = mysqli_fetch_object($ergebnis))
{
	$userid=$row->id;
	echo $userid."<br>";
}
?>


<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">



setInterval(function(){
$('#pilot1').load("groups_server.php?pilot_id=1");
$('#pilot2').load("groups_server.php?pilot_id=2");
$('#pilot3').load("groups_server.php?pilot_id=3");
$('#pilot4').load("groups_server.php?pilot_id=4");
$('#pilot5').load("groups_server.php?pilot_id=5");
$('#pilot6').load("groups_server.php?pilot_id=6");
$('#pilot7').load("groups_server.php?pilot_id=7");
$('#pilot8').load("groups_server.php?pilot_id=8");

 },1000);
 

 
 
 </script>

<div id="pilot1" style="float: left;">Loading...</div>
<div id="pilot2" style="float: left;">Loading...</div>
<div id="pilot3" style="float: left;">Loading...</div>
<div id="pilot4" style="float: left;">Loading...</div>
<div id="pilot5" style="float: left;">Loading...</div>
<div id="pilot6" style="float: left;">Loading...</div>
<div id="pilot7" style="float: left;">Loading...</div>
<div id="pilot8" style="float: left;">Loading...</div>

<?php





if (isset($_GET['delete'])){
echo "Wait....delete";
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

echo $_GET['delete'];	
$headid=$_GET['delete'];
mysqli_query($conn, "DELETE FROM laps WHERE head='".$headid."'");

  


	
}
?>
