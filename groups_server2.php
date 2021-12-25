


<!doctype html>
<html>
 <body >
   <!-- CSS -->
   <link href='jquery-ui.min.css' rel='stylesheet' type='text/css'>

   <!-- Script -->
   <script src='jquery-3.3.1.js' type='text/javascript'></script>
   <script src='jquery-ui.min.js' type='text/javascript'></script>
   <script type='text/javascript'>
   $(document).ready(function(){
     $('.dateFilter').datepicker({
        dateFormat: "yy-mm-dd"
     });
   });
   
   

   
   
   </script>

   <!-- Search filter -->
   <form method='post' action=''>
     Start Date <input type='text' class='dateFilter' name='fromDate' value='<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>'>
 
     End Date <input type='text' class='dateFilter' name='endDate' value='<?php if(isset($_POST['endDate'])) echo $_POST['endDate']; ?>'>

     <input type='submit' name='but_search' value='Search'>
   </form>

   <!-- Employees List -->
   <div style='height: 80%; overflow: auto;' >
 
     <table border='1' width='100%' style='border-collapse: collapse;margin-top: 20px;'>
       <tr>
         <th>Time</th>
         <th>Name</th>
         <th>Realtime</th>
         <th>Placeholder</th>
       </tr>

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
       $emp_query = "SELECT * FROM laps";
       $lipo_query = "SELECT * FROM laps";

       // Date filter
       if(isset($_POST['but_search'])){
          $fromDate = $_POST['fromDate']." 00:00:00";
          $endDate = $_POST['endDate']." 23:59:59";
		  
		  
          //echo $fromDate."<br>";
		  //echo $endDate."<br>";
		  
          if(!empty($fromDate) && !empty($endDate)){
             $emp_query .= " where realtime between '".$fromDate."' and '".$endDate."'  order by id desc";
			 $lipo_query .= " where realtime between '".$fromDate."' and '".$endDate."'  group by pilot_id";
			             //funktioniert $emp_query .= " where realtime between '2021-06-01 00:00:00' and '2021-06-03 00:00:00'";

//lipo_query
echo "<br>Stats:<br>";
$lipoRecords = mysqli_query($conn,$lipo_query);
$akkus=0;
while($lipo = mysqli_fetch_assoc($lipoRecords)){

	 $pilot_id=$lipo['pilot_id'];

//Lipo	
$sql="select count(*) as total from laps where realtime between '".$fromDate."' and '".$endDate."' and pilot_id='".$pilot_id."' and timestamp >60000 ";
$result=mysqli_query($conn,$sql);
$data=mysqli_fetch_assoc($result);

//Fast round
$sql2="select min(timestamp) as fastest from laps where realtime between '".$fromDate."' and '".$endDate."' and pilot_id='".$pilot_id."' and timestamp > 15000 ";
$result2=mysqli_query($conn,$sql2);
$data2=mysqli_fetch_assoc($result2);
echo "Fastest Round ".($data2['fastest']/1000)." ";


//pilot name
$ergebnis4 = mysqli_query($conn, "SELECT * FROM users Where id=".$pilot_id."");
$row4 = mysqli_fetch_object($ergebnis4);
$realname=$row4->pilotname;

echo $realname.":".$data['total']." Lipos<br>";
$akkus=$data['total']+$akkus;
 }
echo "Summe: ".$akkus." Lipos";
echo "<hr color=red>";
					
          }
        }

        // Sort
        //$emp_query .= " ORDER id DESC";
        $employeesRecords = mysqli_query($conn,$emp_query);

        // Check records found or not
        if(mysqli_num_rows($employeesRecords) > 0){
          while($empRecord = mysqli_fetch_assoc($employeesRecords)){
            $id = $empRecord['id'];
            $empName = ($empRecord['timestamp']/1000);
            $pilot_id = $empRecord['pilot_id'];
            $gender = $empRecord['realtime'];
            $email = $empRecord['email'];
            $ergebnis3 = mysqli_query($conn, "SELECT * FROM users Where id=".$pilot_id."");
            $row3 = mysqli_fetch_object($ergebnis3);
	        $realname=$row3->pilotname;
			
			
			
            if($empRecord['timestamp']>60000){
            echo "<tr>";
            echo "<td><hr color=red></td>";
            echo "<td><hr color=red></td>";
            echo "<td><hr color=red></td>";
            echo "<td><hr color=red></td>";
            echo "</tr>";
            }else{


            echo "<tr>";
            echo "<td>". $empName ."</td>";
            echo "<td>". $realname ."</td>";
            echo "<td>". $gender ."</td>";
            echo "<td>". $email ."</td>";
            echo "</tr>";
			}
			
			
			
			
          }
        }else{
          echo "<tr>";
          echo "<td colspan='4'>No record found.</td>";
          echo "</tr>";
        }
        ?>
      </table>
 
    </div>
 </body>
</html>



<?php
/*
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



*/ 

$conn->close();


?>

