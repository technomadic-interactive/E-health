<?php
require('plivo-curl-wrapper.php');
#$api_key="AIzaSyDqnw9lpFlOPzK0Ds5XDlsNVMTYRrbQkZI";#key para google short-link

#comprueba si viene post
if($_POST){
    $latitud = $_POST['lat'];
    $longitud = $_POST['long'];
    echo "viene post";
    echo   $lat=19.4812;
    echo   $long=-99.234;
    $lat=(string) $latitud;
    $long=(string) $longitud;
}
else{
    $lat="19.4812";
    $long = "-99.234";
    echo "no viene post";
}

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

//print_r($address);
//echo $address;
$myAddress = $address['results'][0]['formatted_address'];#obtiene la direccion
$myAddress_short = $address_short['id'];#obtiene el short-link
//echo $myAddress;
//var_dump($myAddress);
//print_r($myAddress);
#elimina los espacios despues de las comas
$search=array(", ",",Méx",".,Mexico");
$replace=array(",","","");
//$myFixedAddress=str_replace($search,$replace,$myAddress);
//echo "####".$myFixedAddress;
#print_r($address);

$destNumb="+525554181711";
//$destNumb="+17864540964";#números destino para recivir el sms de plivo

$message=$myAddress."\r\n".$myAddress_short;#mensaje para el sms de plivo

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
SendMessage($destNumb,$message);        
?>
