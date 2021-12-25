
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





$i=0;
$summe=0;
//GROUP by Heads

echo "Lipos:";
$sql="select count(*) as total from laps where timestamp>120000";
$result=mysqli_query($conn,$sql);
$data=mysqli_fetch_assoc($result);
echo $data['total'];
echo "<hr color=red>";

//$ergebnis = mysqli_query($conn, "SELECT * FROM laps where pilot_id=".$_GET['pilot_id']." order by id desc");
if(isset($_GET['pilot_id'])){

$ergebnis = mysqli_query($conn, "SELECT * FROM laps where pilot_id=".$_GET['pilot_id']." order by id desc");
}else{

$ergebnis = mysqli_query($conn, "SELECT * FROM laps order by id desc");	
}

while($row = mysqli_fetch_object($ergebnis))
{
	$realtime=$row->realtime;
	$timestamp=$row->timestamp;
	$pilot_id=$row->pilot_id;
	$dbid=$row->id;
	$d=date('i', strtotime($realtime));
	

		$d=date('i', strtotime($realtime));
	
	

	
	
	$diff=$oldd-$d;
	if($diff>2){

     //echo "<hr color=red>";
    $i=0;
	 }


	 
	 
	 if($timestamp<70000){
     //echo $d." ".$timestamp."<br>";
	
	
 	 echo "<table border=1  width=260px><tr>";
	 
	 if($i==0){
		  echo "<tr><th align=left><font color=red>".$realtime."</font></th></tr>";
		  $summe=0;
	 }
	 $summe=$timestamp+$summe;
	 //Pilot Name
	 $ergebnis3 = mysqli_query($conn, "SELECT * FROM users Where id=".$pilot_id."");
     $row3 = mysqli_fetch_object($ergebnis3);
	 $realname=$row3->pilotname;
	 
	 echo "<th align=left><a href=http://hainz.ddns.net/delta5c/index.php?page=groups&delete=".$dbid.">[x]</a><a href=http://hainz.ddns.net/delta5c/index.php?page=groups&pilot_id=".$pilot_id."> [".$realname."] </a>".($i+1)." ".($timestamp/1000)." ".($summe/1000)."</th>";

     echo "</tr></table>";	
     echo "</td>";
	 
	 	 if($i==0){
		 echo "</tr>";
	 }
	 $i++;
	 }else{
     //echo "<hr color=red>";
	 $i=0;

	 }	 

     $oldd=$d;
   

}



 

$conn->close();


?>

