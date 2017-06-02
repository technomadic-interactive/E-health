
#include <Wire.h> // Must include Wire library for I2C
#include <SFE_MMA8452Q.h> // Includes the SFE_MMA8452Q library

MMA8452Q accel;

void setup()
{
  Serial.begin(9600);
  Serial.println("MMA8452Q Test Code!");
  accel.init(SCALE_4G); // Uncomment this out if you'd like
  //accel.init(SCALE_8G, ODR_6);
}

void loop(){
if (accel.available()){
  accel.read();
  printCalculatedAccels();
  }
}

void printCalculatedAccels(){ 
  if(accel.cz>3.5){
    Serial.print(accel.cx, 3);
    Serial.print("\t");
    Serial.print(accel.cy, 3);
    Serial.print("\t");
    Serial.print(accel.cz, 3);
    Serial.print("\t");
    Serial.println();
  }
}


