<?php 

require 'config.php';

$busid = $_GET['busid'];
$time = $_GET['time'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$speed = $_GET['speed'];
$status = $_GET['status'];

echo $busid;
echo "<br>";
echo $time;
echo "<br>";
echo $lat;
echo "<br>";
echo $lng;
echo "<br>";
echo $speed;
echo "<br>";
echo $status;


$sql = "INSERT INTO tbl_gps(busid,time,lat,lng,speed,status) 
	VALUES('".$busid."','".$time."','".$lat."','".$lng."','".$speed."','".$status."')";


if($db->query($sql) === FALSE)
	{ echo "Error: " . $sql . "<br>" . $db->error; }

echo "<br>";
echo $db->insert_id;
?>
<?php mysqli_close($db);?>