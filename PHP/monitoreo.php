<?php
include "../../../connect_e_health.php";
	
	$link= db_Connection();
	$result= $link->query("SELECT * FROM gps ORDER BY `ID` DESC LIMIT 10");
?>

<html>
   <head>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="shortcut icon" type="image/x-icon" href="../images/e-health_logo.ico">
      <title>GPS</title>
      <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
   </head>
<body>
   <div class="container">
	    <img src="../images/ECG-heart.png" style="width:170px;height:130px">
	    </br></br>
   
   <h1>Posiciones de GPS</h1>
   <div class="table-responsive">
   		<table class="table table-striped">
		<tr>
			<td>&nbsp;Número&nbsp;</td>
			<td>&nbsp;Fecha&nbsp;</td>
			<td>&nbsp;Latitud&nbsp;</td>
			<td>&nbsp;Longitud&nbsp;</td>
		</tr>

      <?php 
		  if($result!==FALSE){
		     while($row = $result->fetch_assoc()) {
		        printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		           $row["ID"], $row["Fecha"], $row["latitud"], $row["longitud"]);
		     }
		     $link->close();

		  }
      ?>
   
   </table>
   </div>
  </div>
</body>
</html>

