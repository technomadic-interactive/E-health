//Bloque de librerias#include <MAX17043.h>
#include <Wire.h>
#include <SoftwareSerial.h>

String imei;

//Generacion de Objetos

SoftwareSerial Uc20(5,6); //rX, tX 
//Definicion de funciones
void sendMsg();

void setup() {
  Wire.begin();
  Serial.begin(9600);
  Uc20.begin(115200);
  delay(1000);
  sendATCommand("AT+IPR=9600", 100);
  Uc20.begin(9600);

  delay(1000);
  Serial.println("QUC20 inicializado a 115200");
  sessionsInit();

  char c;
  imei;
  Uc20.println(" AT+GSN");     // Send request
  int count = 5;                       // Number of 100ms intervals before 
                                       // assuming there is no more data
  while(count-- != 0) {                // Loop until count = 0

    delay(100);                        // Delay 100ms

    while (Uc20.available() > 0){  // If there is data, read it and reset
       c = (char)Uc20.read();      // the counter, otherwise go try again
       imei += c;
       count = 5;       
    }
  }
  imei.remove(0, 9);
  imei.remove(15, 8);
  Serial.println(imei);
  Serial.println(imei.length());
}

void loop() {
  sendMsg();
  delay(1000);
}


void sessionsInit() {
  //Activar GPS
  sendATCommand("AT+QGPS=1", 100);
  //Activar conexion 3G
  sendATCommand("AT+QHTTPCFG=\"contextid\",1", 100);
  sendATCommand("AT+QHTTPCFG=\"responseheader\",1", 100);
  //sendATCommand("AT+QICSGP=1,1,\"internet.itelcel.com\",\"webgprs\",\"webgprs2002\",1", 100);
  sendATCommand("AT+QICSGP=1,1,\"internet.movistar.mx\",\"movistar\",\"movistar\",1", 100);
  sendATCommand("AT+QIACT=1", 100);
}


void sessionsClose() {
  sendATCommand("AT+QGPSEND", 100); //Termina sesion de GPS
  sendATCommand("AT+QIDEACT=1", 100); //Termina sesion de 3G
  delay(1000);
}

void restartUC() {
  sessionsClose();
  sendATCommand("AT+QGPSEND", 100);
  sendATCommand("AT+QIDEACT=1", 100);
  delay(1000);
}

void powerOff() {
  sessionsClose();
  sendATCommand("AT+QPOWD", 300);
}


//Funcion de envio de comandos AT
String sendATCommand(String command, int ms) {
  char c;
  String res;
  Uc20.println(command);     
  int count = 5;                      
  // assuming there is no more data
  while (count-- != 0) {               

    delay(100);                       

    while (Uc20.available() > 0) { 
      c = (char)Uc20.read();     
      res += c;
      count = 5;
    }
  }
  Serial.println(res);
  return res;
}


//Limpia una respuesta de un comando para dejar solo la respuesta
String getBodyResponse(String msg) {
  int startW = 0, endsW = 0;
  String bodyRes;

  startW = msg.indexOf('\n');
  endsW = msg.indexOf('\n', startW + 1);
  return msg.substring(startW + 1, endsW);
}

//Limpia una respuesta de un comando para dejar solo la respuesta
//Esta sobrecarga permite lidiar con las diferencias entre comandos
//de GPS y de Datos 3G
String getBodyResponse(String msg, int mode) {
  int startW = 0, endsW = 0;
  String bodyRes;

  startW = msg.indexOf('\n');
  //Serial.println(startW);
  if (mode == 1) {
    endsW = msg.indexOf('\n', startW + 2);
  } else {
    endsW = msg.indexOf('\n', startW + 1);
  }
  //Serial.println(endsW);
  return msg.substring(startW + 1, endsW);
}

//funcion para quitar la cabecera de una respuesta a un comando
String getDataResponse(String data) {
  int ndx = 0;
  ndx = data.indexOf(':');
  data.trim();
  return data.substring(ndx + 1);
}

//Limpia una respuesta de un comando para dejar solo la respuesta
//Esta sobrecarga permite lidiar con los formatos de respuestas 
//a comandos que necesitaban argumentos
String getBodyReadResponse(String msg) {
  int startW = 0, endsW = 0, fLn = 0;
  String bodyRes;

  fLn = msg.indexOf('\n');
  startW = msg.indexOf('\n', fLn + 1);
  endsW = msg.indexOf('\n', startW + 1);
  return msg.substring(startW + 1, endsW);
}


//Funcion de envio de datos a traves de 3G
void sendMsg() {
  String act;
  String res, atcomm;
  res = "Latitud=24.345&Longitud=12.343&Fix=Ant&IMEI=";
  res += imei;
  sendATCommandWithResponse("AT+QHTTPURL=77,77", "http://technomadic.westcentralus.cloudapp.azure.com/E-health/PHP/add_data.php");
  delay(300);
  sendATCommand("AT+QIGETERROR", 100);
  atcomm = "AT+QHTTPPOST=";
  atcomm += res.length();
  atcomm += ",80,80";
  Serial.println(atcomm);
  sendATCommandWithResponse(atcomm, res);
  delay(30);
  sendATCommand("AT+QIGETERROR", 100);
  delay(20);
  Serial.println(sendATCommand("AT+QHTTPREAD=80", 100));
  delay(30);
  sendATCommand("AT+QHTTPREAD=30",100);
  delay(30);
}

//Funcion para enviar comandos que necesiten argumentos una vez que son enviados
void sendATCommandWithResponse(String command, String response) {
  char incoming_char;
  Uc20.println(command);
  delay(500);
  Serial.println(Uc20.available());
  while (Uc20.available() > 0) {
    incoming_char = Uc20.read();
    Serial.print(incoming_char);
  }
  Uc20.println(response);
  delay(300);
  Serial.println(Uc20.available());
  while (Uc20.available() > 0) {
    incoming_char = Uc20.read();
    Serial.print(incoming_char);
  }
  delay(500);
}

