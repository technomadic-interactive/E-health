<?php
require('plivo-curl-wrapper.php');
session_start();
#$api_key="AIzaSyDqnw9lpFlOPzK0Ds5XDlsNVMTYRrbQkZI";#key para google short-link

$IMEI=$_SESSION["IMEI"];
$latitud=$_SESSION["latitud"];
$longitud=$_SESSION["longitud"];
$fix=$_SESSION["fix"];
$status=$_SESSION['status'];


$lat=(string) $latitud;
$long=(string) $longitud;

printf("\n");

$url1 = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";#url para servicio de convertir coordenadas a direccion

$url2 = "https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyDqnw9lpFlOPzK0Ds5XDlsNVMTYRrbQkZI";#url para servicio de obtenener el shortlink
 
$longUrl=array("longUrl"=>"http://maps.google.com/?q=".$lat.",".$long);#url link largo para ser achicado

$data_string = json_encode($longUrl);#convertir a json para enviar en el post

#envia el post que obtiene el short-link

$curl_short = curl_init();
curl_setopt($curl_short, CURLOPT_URL, $url2);
curl_setopt($curl_short, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl_short, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Accept: application/json'));
curl_setopt($curl_short, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl_short, CURLOPT_RETURNTRANSFER, true);
$curlData_short = curl_exec($curl_short);
curl_close($curl_short);
$address_short = json_decode($curlData_short,true);

#envia el post que obtiene la direccion

$curl_address = curl_init();
curl_setopt($curl_address, CURLOPT_URL, $url1);
curl_setopt($curl_address, CURLOPT_HEADER, false);
curl_setopt($curl_address, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl_address, CURLOPT_ENCODING, "");
curl_setopt($curl_address, CURLOPT_RETURNTRANSFER, true);
$curlData_address = curl_exec($curl_address);
curl_close($curl_address);
$address = json_decode($curlData_address,true);

$myAddress = $address['results'][1]['formatted_address'];#obtiene la direccion
$myAddress_short = $address_short['id'];#obtiene el short-link
//echo $myAddress;
var_dump($myAddress);
print_r($myAddress);
#elimina los espacios despues de las comas
$search=array(", ",",Méx",".,Mexico");
$replace=array(",","","");


//$destNumb="+525554181711";

include "../connect_e_health.php";
$link=db_Connection();
$result= $link->query("SELECT Numero1, Numero2, Numero3, Numero4, Numero5 FROM contactos WHERE IMEI=".$IMEI."");
if($result){
    while ($row = $result->fetch_assoc()) {
             $res=$row["Numero1"];
             $res2=$row["Numero2"];
             $res3=$row["Numero3"];
             $res4=$row["Numero4"];
             $res5=$row["Numero5"];
    }
}

$result2= $link->query("SELECT Nombre FROM datos WHERE IMEI=".$IMEI."");
if($result){
    while ($row = $result->fetch_assoc()) {
             $nombre=$row["Nombre"];
    }
}

echo $status;

printf("\n");
$link->close();
$destNumb=$res;
$destNumb2=$res2;
$destNumb3=$res3;
$destNumb4=$res4;
$destNumb5=$res5;
echo $destNumb;
printf("\n");
echo $destNumb2;
printf("\n");
echo $destNumb3;
printf("\n");
echo $destNumb4;
printf("\n");
echo $destNumb5;

//$destNumb="+17864540964";#números destino para recivir el sms de plivo
printf("\n");
$message=$myAddress."\r\n".$myAddress_short;#mensaje para el sms de plivo
$message2="Tu familiar ".$nombre." ha sido atendido";
echo $message;
printf("\n");

#envia SMS plivo    

function SendMessage($number, $message)
    {

        $auth_id = "MAZTKYMJIWY2Y3YZNIYZ";
        $auth_token = "YjdlOGJlZTg3NzBhZTI1ZTI0MmY0NWNhNTRmN2U5";

        $p = new RestAPI($auth_id, $auth_token);
     
        // Send a message
        $params = array(
                //'src' => '+14083598743',
                'src' => '14083598743',
                'dst' => $number,
                'text' => $message,
                'type' => 'sms',
            );
        
        $response = $p->send_message($params);

//        return array_shift(array_values($response)) == "202";
    }

if ($status=="Pendiente"){
    SendMessage($destNumb,$message);        
    SendMessage($destNumb2,$message);
    SendMessage($destNumb3,$message);
    SendMessage($destNumb4,$message);
    SendMessage($destNumb5,$message);
}

if ($status=="Atendido"){
    SendMessage($destNumb,$message2);        
    SendMessage($destNumb2,$message2);
    SendMessage($destNumb3,$message2);
    SendMessage($destNumb4,$message2);
    SendMessage($destNumb5,$message2);
}

?>
