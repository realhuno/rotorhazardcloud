<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">



setInterval(function(){
      $('#test').load('groups_server.php');
 },1000);
 
 
 </script>

<div id="test">Loading...</div>
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
