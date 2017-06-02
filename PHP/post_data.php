<?php
   	include "../../../connect_e_health.php";
   	
   	$link=db_Connection();

	  $latitud=$_POST["latitud"];
	  $longitud=$_POST["longitud"];
	  $peticion="INSERT INTO gps VALUES (NULL, NULL, $latitud, $longitud)";

    $link->query($peticion);

   	$link->close();
/*
    $send_data -> temperatura = $temperatura;
    $send_data -> presion_distolica = $presion_dis;
    $send_data -> presion_sistolica = $presion_sis;
    $send_data -> pulso = $pulso;

    $myJSON = json_encode($send_data);

    $archivo = fopen("data.json", "w");
    fwrite($archivo, $myJSON);
    fclose($archivo);
*/
   	header("Location: monitoreo.php");
?>


