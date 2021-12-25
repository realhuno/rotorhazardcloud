<?php

								
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "test";



	
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$ergebnis = mysqli_query($conn, "SELECT * FROM seat");
																
?>
<h1>Seat Configuration</h1>

<?php
while($row = mysqli_fetch_object($ergebnis))
{
	$userid=$row->id;
	$seatid=$row->userid;
	
	echo "Seat: ".$userid."<br>";
	
	?>
															

<form name="seatconfig" action="index.php?page=tracker&post=true" method="post">
<input type="hidden" name="seatid" value=<?php echo $userid;?> />
<select id="userid" name="userid">
  <?php
  
$ergebnis2 = mysqli_query($conn, "SELECT * FROM users");
while($row2 = mysqli_fetch_object($ergebnis2))
{
	$userid2=$row2->id;
	$username2=$row2->pilotname;
	
	if($userid2==$seatid){
	echo "<option value=".$userid2." selected>".$username2."</option>";
	}else{
	echo "<option value=".$userid2.">".$username2."</option>";
	}
	
}
	
							
								
								?>
								
  


</select>

<input type="submit" name="submit" value="Register" />
</form>
</div>
	<?php
	
	
}
	
	
 if (isset($_REQUEST['post'])){
 echo "POST";
$sql = "UPDATE seat SET userid = '".$_POST['userid']."' WHERE id = '".$_POST['seatid']."'";
mysqli_query($conn,$sql);
echo $_POST['seatid']."_".$_POST['userid'];

?>
<script type="text/javascript">
<!--
window.location = "http://hainz.ddns.net/delta5c/index.php?page=tracker";
//–>
</script>
<?php
 }else{
	 echo "Setup "; 
 }
	
	
							
?>
														
