<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Users:</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
						
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$ergebnis = mysqli_query($conn, "SELECT * FROM users");

echo "<table class=\"blueTable\">
<tr>
<th>Username</th>
<th>Pilotname</th>
<th>Name</th>
<th>Email</th>
</tr>";															
																
while($row = mysqli_fetch_object($ergebnis))
{

echo "<tr>";
echo "<td>" .$row->username. "</td>";
echo "<td>" .$row->pilotname. "</td>";
echo "<td>" .$row->name. "</td>";
echo "<td>" .$row->email. "</td>";
echo "</tr>";
}
echo "</table>";
	
?>