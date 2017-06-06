<?php
      session_start();
   	include "../../../connect_e_health.php";
   	
   	$link=db_Connection();

	 $nombre=$_POST["Nombre"];
       $apellido=$_POST["Apellido"];
       $IMEI=$_POST["IMEI"];
       $direccion=$_POST["Direccion"];
       $year=$_POST["Year"];
       $month=$_POST["Month"];
       $day=$_POST["Day"];
       $telefono=$_POST["Telefono"];
       $sexo=$_POST["Sexo"];
       $latitud=$_POST["Latitud"];
       $longitud=$_POST["Longitud"];
       $fix=$_POST["Fix"];
       $contacto1=$_POST["Contacto1"];
       $numero1=$_POST["Numero1"];
       $contacto2=$_POST["Contacto2"];
       $numero2=$_POST["Numero2"];
       $contacto3=$_POST["Contacto3"];
       $numero3=$_POST["Numero3"];
       $contacto4=$_POST["Contacto4"];
       $numero4=$_POST["Numero4"];
       $contacto5=$_POST["Contacto5"];
       $numero5=$_POST["Numero5"];
       $_SESSION['IMEI']=$IMEI;
       $_SESSION['IMEI']=$nombre;
       if ($nombre){
           $peticion="INSERT INTO datos VALUES (NULL, '" .$IMEI."', '" .$nombre."', '" .$apellido."',$year, $month, $day, '" .$direccion."','" .$telefono."', '" .$sexo."' )";        
           header("Location: /E-health/PHP/monitoreo.php");
       }

      if ($contacto1){
          $peticion2="INSERT INTO contactos VALUES ('" .$IMEI."', '" .$contacto1."', '" .$numero1."', '" .$contacto2."','" .$numero2."', '" .$contacto3."', '" .$numero3."', '" .$contacto4."', '" .$numero4."', '" .$contacto5."', '" .$numero5."' )";
          header("Location: /E-health/PHP/monitoreo.php"); 
      }

      if ($latitud){
          $peticion="INSERT INTO incidentes VALUES ('" .$IMEI."', NULL, $latitud, $longitud, '" .$fix."')"; 
          include "getAddressSms";
      }

    $link->query($peticion);
    $link->query($peticion2);

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
   	
?>

