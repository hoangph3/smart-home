#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"

#define LED_D6 12 //LED RED
#define LED_D7 13 //LED GREEN
#define LED_D8 15 //LED BLUE
DHT dht(14, DHT11);

//Config wifi
const char* ssid = "MIT";
const char* password = "28062017";

//Config ip localhost
const char *host = "http://192.168.1.18/";

void setup() {
  Serial.begin(115200);
  delay(500);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  Serial.println("");

  dht.begin();
  pinMode(4, INPUT); //pin Flame
  pinMode(2, INPUT); //pin PIR
  
  pinMode(0,OUTPUT); //pin Buzz
  digitalWrite(0, LOW);
  
  pinMode(LED_D6,OUTPUT);
  digitalWrite(LED_D6, LOW);
  
  pinMode(LED_D7,OUTPUT);
  digitalWrite(LED_D7, LOW);
  
  pinMode(LED_D8,OUTPUT);
  digitalWrite(LED_D8, LOW);

  Serial.print("Connecting");
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");
    delay(250);
  }
  Serial.println("");
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println();
}

int flameSensor() {
  int flameValue = digitalRead(4);
  Serial.print("Flame Sensor Value: ");
  Serial.println(flameValue);
  delay(1);
  return flameValue;
}

int pirSensor() {
  int pirValue = digitalRead(2);
  Serial.print("PIR Sensor Value: ");
  Serial.println(pirValue);
  delay(1);
  return pirValue;
}

void dhtSensor(){
  float h = dht.readHumidity();    
  float t = dht.readTemperature();
  Serial.println();
  Serial.print("Humidity: ");
  Serial.println(h);
  Serial.print("Temperature: ");
  Serial.println(t);
}

void loop() {
  HTTPClient http; //http method
  //GetData from Database
  String GetAddress, LinkGet, getData, LEDStatResultSend;
  int id = 0; //ID in Database
  GetAddress = "Smart_Home/GetData.php"; 
  LinkGet = host + GetAddress; //url
  getData = "ID=" + String(id);
  Serial.println("----------------Connect to Server-----------------");
  Serial.println("Get LED Status from Server or Database");
  Serial.print("Request Link : ");
  Serial.println(LinkGet);
  http.begin(LinkGet); //--> Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");    //Specify content-type header
  int httpCodeGet = http.POST(getData); //--> Send the request
  String payloadGet = http.getString(); //--> Get the response payload from server
  Serial.print("Response Code : "); //--> If Response Code = 200 means Successful connection, if -1 means connection failed. For more information see here : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
  Serial.println(httpCodeGet); //--> Print HTTP return code
  Serial.print("Returned data from Server : ");
  Serial.println(payloadGet); //--> Print request response payload

  //Read payloadGet and Write data
  if(payloadGet.substring(0,1)=="R"){
    analogWrite(LED_D6, payloadGet.substring(1).toInt() * 1023 / 100);
    analogWrite(LED_D7, 0);
    analogWrite(LED_D8, 0);
    LEDStatResultSend = payloadGet.substring(1); 
  }
  if(payloadGet.substring(0,1)=="G"){
    analogWrite(LED_D7, payloadGet.substring(1).toInt() * 1023 / 100);
    analogWrite(LED_D6, 0);
    analogWrite(LED_D8, 0);
    LEDStatResultSend = payloadGet.substring(1); 
  }
  if(payloadGet.substring(0,1)=="B"){
    analogWrite(LED_D8, payloadGet.substring(1).toInt() * 1023 / 100);
    analogWrite(LED_D7, 0);
    analogWrite(LED_D6, 0);
    LEDStatResultSend = payloadGet.substring(1); 
  }
  if(payloadGet.substring(0,1)=="A"){
    analogWrite(LED_D6, payloadGet.substring(1).toInt() * 1023 / 100);
    analogWrite(LED_D7, payloadGet.substring(1).toInt() * 1023 / 100);
    analogWrite(LED_D8, payloadGet.substring(1).toInt() * 1023 / 100);
    LEDStatResultSend = payloadGet.substring(1); 
  }

//  analogWrite(LED_D8, payloadGet.toInt() * 1023 / 100); 
//  LEDStatResultSend = payloadGet;
//  if (payloadGet == "1") {
//    digitalWrite(LED_D8, HIGH); //--> Turn on Led
//    
//  }
//  if (payloadGet == "0") {
//    digitalWrite(LED_D8, LOW); //--> Turn off Led
//    LEDStatResultSend = payloadGet;
//  }
  //----------------------------------------

  //----------------------------------------Sends LED status feedback data to server
  Serial.println();
  Serial.println("Sending LED Status to Server");
  String postData, LinkSend, SendAddress;
  SendAddress = "Smart_Home/getLEDStatFromNodeMCU.php";
  LinkSend = host + SendAddress;
  postData = "getLEDStatusFromNodeMCU=" + LEDStatResultSend;
  Serial.print("Request Link : ");
  Serial.println(LinkSend);
  http.begin(LinkSend); //--> Specify request destination
  http.addHeader("Content-Type", "application/x-www-form-urlencoded"); //--> Specify content-type header
  int httpCodeSend = http.POST(postData); //--> Send the request
  String payloadSend = http.getString(); //--> Get the response payload
  Serial.print("Response Code : "); //--> If Response Code = 200 means Successful connection, if -1 means connection failed
  Serial.println(httpCodeSend); //--> Print HTTP return code
  Serial.print("Returned data from Server : ");
  Serial.println(payloadSend); //--> Print request response payload
  //----------------------------------------
  
  Serial.println("----------------Closing Connection----------------");
  http.end(); //--> Close connection
  Serial.println();
  
  //Buzz
  dhtSensor();
  int pirValue = pirSensor();
  int flameValue = flameSensor();
  if(pirValue==1 || flameValue==0){
    digitalWrite(0, HIGH);
  }
  else{
    digitalWrite(0, LOW);
  }
  Serial.println("Please wait 1 seconds for the next connection.");
  delay(1000);
  Serial.println();
}
