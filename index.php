
<!DOCTYPE HTML>
<html>
<head>
<title>BOSSO|DASHBOARD</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
</head>
<style>
</style>
<body>
    <?php date_default_timezone_set('Africa/Lagos'); ?>
    <script>
        function autoRefresh() {
            window.location = window.location.href;
        }
        setInterval('autoRefresh()', 30000);
    </script>
     <div class="container">
<h1 class="text-info" style="text-align:center; padding-top:5px;padding-buttom:-10px" ><b>TRANSPORTATION MODEL - BOSSO DASHBOARD </b></h1>
	<section style="text-align:center; font-size:37px; ">

<body onload="startTime()">


<div id="txt"; style="font-size:50px; padding-top:-10
px"></div>

<script>
function startTime() {
  const today = new Date();
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>
	<table class="table table-sm table-striped table-bordered">
            <tr class="table-dark">
                <th style="font-size:25px">BUS ID</th>
                <th style="font-size:25px">DEPARTURE TIME</th>
                <th style="font-size:25px">EXPECTED ARRIVAL TIME</th>
                
                <th style="font-size:25px">STATUS</th>
                
            </tr>
             
               <!--estimation of arrival time for BUS 01-->
<?php
    include 'config.php';	
	  
     //bus ID 01
	 $id = '1';
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
  
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
	
	  
	  $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
//if (($pb_lat==$lat) AND ($pb_long==$lng )){
//		$status = "arrived";
//	} else {
//		$status ="on the way";
//	}


//	if (($lat == 9.568833) AND ($lng == 6.493959)){
//	    $status = "At Gida Mangoro";
//	}  
//	if (($lat == 9.552768) AND ($lng == 6.474485)){
//	    $status = "At Dama";
//	} 
//	if (($lat == 9.537187) AND ($lng == 6.467578)){
//	    $status = " At Gida Nkawo";
//	}  
//	if (($lat == 9.617186) AND ($lng == 6.528086)){
//	    $status = "At Kure Market";
//	}  
//	if (($lat == 9.573923) AND ($lng == 6.498618)){
//	    $status = "At NECO";
//	}  
	  $lng = $lng/$eva_rad;
  $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
    if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
    
    //	if (($distance <= 20)) {
	   // $status = "Bus has Arrived";
//	}  
    
    
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
		date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		
		//echo $edt;
		?></td>
         <td> <?php 
        
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'sec';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
        <!--<td> <?php echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 
<?PHP } ?> 

     <!-- estimation for BUS ID 1 ends here -->
     
     
     <!-- ESTIMATION OF BUS 02 ARRIVAL TIME STARTS HERE -->
     <?php
	
	  
     //bus ID 02
	 $id = '2'; 
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
   
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
	
	  $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
	  
	  $lng = $lng/$eva_rad;
   $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
   if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
   
   
    //	if (($distance <= 20)) {
	 //   $status = "Bus has Arrived";
//	} 
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
		
		date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		
		//echo $edt;
		?></td>
        <td> <?php 
        //$s=$s/100;
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'sec';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
        <!--<td> <?php echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 
<?PHP } ?>    
     <!-- ESTIMATION OF BUS 02 ENDS HERE -->
     
     
     <!-- ESTIMATION OF BUS 03 ARRIVAL TIME STARTS HERE -->
     <?php
    	
	  
     //bus ID 03
	 $id = '3';
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
    
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
	
	  $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
	  $lng = $lng/$eva_rad;
   $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
    if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
    
    
    //	if (($distance <= 20)) {
	  //  $status = "Bus has Arrived";
	//} 
	
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
	date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		
		//echo $edt;
		?></td>
         <td> <?php 
        //$s=$s/100;
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'secs';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
      <!--<td> <?php echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 
<?PHP } ?>    
     
     <!-- ESTIMATION OF BUS 03 ENDS HERE -->
     
     
     <!-- ESTIMATION OF BUS 04 ARRIVAL TIME STARTS HERE -->
     <?php
	
	  
     //bus ID 04
	 $id = '4';
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
   
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
	
	   	  $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
	  $lng = $lng/$eva_rad;
   $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
    
    if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
    
    //	if (($distance <= 20)) {
	  //  $status = "Bus has Arrived";
//	} 
    
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
	date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		
		//echo $edt;
		?></td>
         <td> <?php 
       
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'sec';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
        <!--<td> <?php echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 
    
 <?PHP } ?>     
     
     <!-- ESTIMATION OF BUS 04 ENDS HERE -->
     
     
     <!-- ESTIMATION OF BUS 05 ARRIVAL TIME STARTS HERE -->
     <?php
	
	  
     //bus ID 05
	 $id = '5';
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
    
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
		  $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
	  $lng = $lng/$eva_rad;
   $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
    if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
    
    
    //	if (($distance <= 20)) {
	 //   $status = "Bus has Arrived";
//	} 
    
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
	date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		
		//echo $edt;
		?></td>
         <td> <?php 
        
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'sec';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
       <!--<td> <?php echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 
     
<?PHP } ?>    
     
     <!-- ESTIMATION OF BUS 05 ENDS HERE -->
     
     <!-- ESTIMATION OF BUS 06 ARRIVAL TIME STARTS HERE -->
     <?php
    
	  
     //bus ID 06
	 $id = '6';
     //initialize the distance, speed and time of departure
	 
   // echo $id;
	$result = mysqli_query($db,"SELECT * FROM tbl_gps WHERE busid='$id'") or die ("error" . mysqli_error($db));
  if($result){				
	while($row = mysqli_fetch_assoc($result)){
			 $lat =$row['lat'];
			// echo $id;
			 $edt = $row['time'];
			 $s= $row['speed'];
			 $lng= $row['lng'];
			    //$edt = $edt1;
				//$spd = $spd1;
				//$dst = $dst1;
             //echo 'read nama namae'; echo '<br>';
		  }
		  
   
     // Estimated Arrival time is evaluated here using data generated via the cordinate for the speed and distance 
     // estracted from the database
        //echo 'last lat ='. $lat; echo '<br>';
        //echo 'last departure time ='. $edt; echo '<br>';
       // echo 'last speed ='. $s;   echo '<br>';
       // echo 'last longitude ='. $lng;  echo '<br>';
  
        $r= 6378.8;
    	$eva_rad=57.29577951;
	
   
	
	   $pb_long = 6.523899;
	  $pb_lat = 9.653514;
	  //lat range 9.536199
	  // long range 6.523449
	  
	  $lng = $lng/$eva_rad;
   $lat = $lat/$eva_rad;
	  
   $pb_long1 = $pb_long/$eva_rad;
   $pb_lat1 = $pb_lat/$eva_rad;
	
    $distance = $r *acos(sin($pb_lat1)*sin($lat) + cos($pb_lat1)*cos($lat)*cos($pb_long1 - $lng));
    
    
    if ((($lat<=9.653514) AND ($lat>=$pb_lat )) and (($lng<=6.523899) AND ($lng>=$pb_long ))){
		$status = "Bus has arrived";
	} else {
		$status ="on the way";
	}
    
    //	if (($distance <= 20)) {
	 //   $status = "Bus has Arrived";
//	} 
    
//	if($s == 0.00){
//		$est_arriv_time = 0.00;
//	}else{
//		$est_arriv_time = $distance/$s;	 
//	}
	
	//echo 'estimated distance ='. $distance; echo'<br>';
	//echo 'estimated arrival time ='. $est_arriv_time;
   
	
		?>	
		
		
      <tr>
        <td> <?php echo $id; ?></td>
		<td> <?php
		date_default_timezone_set('Africa/Lagos');
		
		$count='3';
	if ($edt==$count){
			$edt= date("h:m:sa");
			$sql = "UPDATE tbl_dept SET time='$edt' WHERE id='$id'";

               if (mysqli_query($db, $sql)) {
                   echo $edt;
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
			//echo $edt;
		}else{
			$result = mysqli_query($db,"SELECT * FROM tbl_dept WHERE id='$id'") or die ("error" . mysqli_error($conn));
            if($result){				
        	     while($row = mysqli_fetch_assoc($result)){
			     $edt =$row['time'];
			    }   
			}
			echo $edt;
		}
		 
		
		//echo $edt;
		?></td>
        <td> <?php 
        //$s=$s/100;
        if($s == 0.00){
		      $est_arriv_time = 0.00;
		      echo $est_arriv_time.'sec';
	       }else{
			   $rem = $distance%$s;
		      $est_arriv_time = floor($distance/$s);
			     if ($est_arriv_time < 60){
					 echo $est_arriv_time.'mins'.' '.$rem.'secs';
				 }else{
					 $arriv = floor($est_arriv_time/60);
					 $rem1 = $est_arriv_time%60;
					   echo $arriv. 'hours'.' '.$rem1.'mins'.' '.$rem.'secs';
				 }
			  
	      }
        
        
        ?></td>
		<!--<td> <?php //echo  $distance; ?></td>-->
        <!--<td> <?php //echo $s; ?></td>-->
		 <td><?php echo $status; ?> </td>
     </tr> 

<?PHP } ?>      
     
     <!-- ESTIMATION OF BUS 06 ENDS HERE -->
             
        </table>
        </div>
        
    </script>
    
</body>
</html>

</script>
    </section>
</body>
</html>
