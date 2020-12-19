#include <Wire.h>
#include <Ewma.h>               
#include <Adafruit_BMP085.h>
#include "UbidotsESPMQTT.h"
#include "PubSubClient.h"
#include <LiquidCrystal_I2C.h>
#include "DHT.h"

#define TOKEN "BBFF-GXxIkg7SN4gF9OKn5bVKyV8alQDdDl" 
#define DEVICE_LABEL "esp8266"
#define WIFINAME "MIT" 
#define WIFIPASS "28062017"
#define VARIABLE_LABEL1 "temperature"   
#define VARIABLE_LABEL2 "pressure"
#define VARIABLE_LABEL3 "humidity"

LiquidCrystal_I2C lcd(0x27, 16, 4);
DHT dht(12, DHT11);
Adafruit_BMP085 bmp;
Ewma adcFilter1(0.01);

int ENA = 14;
int IN1 = 0;
int IN2 = 2;
float e = 0, uk = 0, uk1 = 0;
float pressure = 0, y = 0, temperature = 0, humidity = 0;
float r = 0, MIN_PRESSURE = 0, MAX_PRESSURE = 0;
const int Ts = 150; 
int duty = 0; 
int brillo=0, brillo2=0;
const int ERROR_VALUE = 65535;

const uint8_t NUMBER_OF_VARIABLES = 3;
char * variable_labels[NUMBER_OF_VARIABLES] = {VARIABLE_LABEL1, VARIABLE_LABEL2, VARIABLE_LABEL3};
int seguro=3;

float estadoout1;
float estadoout2;
float estadoout3;

float value;
uint8_t variable;

Ubidots ubiClient(TOKEN);

WiFiClient client;

void callback(char* topic, byte* payload, unsigned int length) {
  char* variable_label = (char *) malloc(sizeof(char) * 30);;
  get_variable_label_topic(topic, variable_label);
  value = btof(payload, length);
  set_state(variable_label);
  execute_cases();
  free(variable_label);  
}

// Parse topic to extract the variable label which changed value
void get_variable_label_topic(char * topic, char * variable_label) {
  Serial.print("topic:");
  Serial.println(topic);
  sprintf(variable_label, "");
  for (int i = 0; i < NUMBER_OF_VARIABLES; i++) {
    char * result_lv = strstr(topic, variable_labels[i]);
    if (result_lv != NULL) {
      uint8_t len = strlen(result_lv);      
      char result[100];
      uint8_t i = 0;
      for (i = 0; i < len - 2; i++) { 
        result[i] = result_lv[i];
      }
      result[i] = '\0';
      Serial.print("Label is: ");
      Serial.println(result);
      sprintf(variable_label, "%s", result);
      break;
    }
  }
}

// cast from an array of chars to float value.
float btof(byte * payload, unsigned int length) {
  char * demo_ = (char *) malloc(sizeof(char) * 10);
  for (int i = 0; i < length; i++) {
    demo_[i] = payload[i];
  }
  return atof(demo_);
}

// State machine to use switch case
void set_state(char* variable_label) {
  variable = 0;
  for (uint8_t i = 0; i < NUMBER_OF_VARIABLES; i++) {
    if (strcmp(variable_label, variable_labels[i]) == 0) {
      break;
    }
    variable++;
  }
  if (variable >= NUMBER_OF_VARIABLES) variable = ERROR_VALUE; // Not valid
}

// Function with switch case to determine which variable changed and assigned the value accordingly to the code variable
void execute_cases() {  
  switch (variable) {
    case 0:
      estadoout1 = value;
      Serial.println(estadoout1);
      Serial.println();
      break;
    case 1:
      estadoout2 = value;
      Serial.println(estadoout2);
      Serial.println();
      break;
    case 2:
      estadoout3 = value;
      Serial.println(estadoout3);
      Serial.println();
      break;
   
    case ERROR_VALUE:
      Serial.println("error");
      Serial.println();
      break;
    default:
      Serial.println("default");
      Serial.println();
  }

}
/****************************************

 * Main Functions

 ****************************************/

void checkBMP180(){ 
  for(byte i=0; i<50; i++){ 
    pressure = bmp.readPressure(); 
    y = adcFilter1.filter(pressure); 
    temperature = bmp.readTemperature();
    MIN_PRESSURE = y;
    MAX_PRESSURE = y + 30;
    r = y + 20; 
  }
}

void PIDController(){
  e = r - y;        
  uk1 = uk + 0.78*e; 
  uk1 = constrain(uk1, 4, 12);
  uk = uk1;
  duty = map(uk1, 4, 12, 341, 1023);
  analogWrite(IN1, 1023);
  analogWrite(IN2, 0);
  analogWrite(ENA, duty); 
}

bool processBMP180(){
  pressure = bmp.readPressure();
  y = adcFilter1.filter(pressure);
  temperature = bmp.readTemperature();
  if((r<MIN_PRESSURE)||(r>MAX_PRESSURE)){
    Serial.println("Out of range!");
    while (1) {}
  }
}

void blink(){
  digitalWrite(13, HIGH);
  delay(50);
  digitalWrite(13, LOW);
}

void setup() {
  Serial.begin(115200);
  // put your setup code here, to run once:
  if (!bmp.begin()) 
    Serial.println("Could not find a valid BMP085 sensor, check wiring!");
  checkBMP180();
  
  ubiClient.ubidotsSetBroker("industrial.api.ubidots.com"); // Sets the broker properly for the business account
  ubiClient.setDebug(true); // Pass a true or false bool value to activate debug messages

  ubiClient.wifiConnection(WIFINAME, WIFIPASS);
  ubiClient.begin(callback);
  if(!ubiClient.connected()) {
    ubiClient.reconnect();
  }

  char* deviceStatus = getUbidotsDevice(DEVICE_LABEL);
  if (strcmp(deviceStatus, "404") == 0) {
    ubiClient.add(VARIABLE_LABEL1, 0); //Insert your variable Labels and the value to be sent
    ubiClient.ubidotsPublish(DEVICE_LABEL);
    ubiClient.add(VARIABLE_LABEL2, 0); //Insert your variable Labels and the value to be sent
    ubiClient.ubidotsPublish(DEVICE_LABEL);
    ubiClient.add(VARIABLE_LABEL3, 0); //Insert your variable Labels and the value to be sent
    ubiClient.ubidotsPublish(DEVICE_LABEL);
    ubiClient.loop();
  }
  ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL1); //Insert the Device and Variable's Labels
  ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL2); //Insert the Device and Variable's Labels
  ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL3); //Insert the Device and Variable's Labels
  Serial.println(variable_labels[1]);

  pinMode(ENA, OUTPUT);
  pinMode(IN1, OUTPUT);
  pinMode(IN2, OUTPUT);
  pinMode(13, OUTPUT);

  // initialize the LCD
  lcd.init();
  // Turn on the blacklight and print a message.
  lcd.backlight();
  lcd.print("Pressure: ");
  dht.begin();
}

void loop() {
  blink();
  humidity = dht.readHumidity();
  lcd.setCursor(0,1);
  lcd.print(pressure);
  lcd.print(" ");
  lcd.print("Pa");
  processBMP180();
  PIDController();
  
  Serial.print(y);
  Serial.print("\t");
  Serial.println(r);
  delay(Ts);
  
  if(!ubiClient.connected()) {
    ubiClient.reconnect();
    ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL1); 
    ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL2); 
    ubiClient.ubidotsSubscribe(DEVICE_LABEL,VARIABLE_LABEL3); 
  }
  ubiClient.add(VARIABLE_LABEL1, temperature);
  ubiClient.add(VARIABLE_LABEL2, pressure);
  ubiClient.add(VARIABLE_LABEL3, humidity);
  ubiClient.ubidotsPublish(DEVICE_LABEL);
}

char* getUbidotsDevice(char* deviceLabel) {
  char* data = (char *) malloc(sizeof(char) * 700);
  char* response = (char *) malloc(sizeof(char) * 400);
  sprintf(data, "GET /api/v1.6/devices/%s/", deviceLabel);
  sprintf(data, "%s HTTP/1.1\r\n", data);
  sprintf(data, "%sHost: industrial.api.ubidots.com\r\nUser-Agent:io/1.0\r\n", data);
  sprintf(data, "%sX-Auth-Token: %s\r\nConnection: close\r\n\r\n", data, TOKEN);
  if (client.connect("industrial.api.ubidots.com", 80)) {
    client.println(data);
  } else {
    return "e";
  }
  free(data);
  int timeout = 0;
  while(!client.available() && timeout < 5000) {
    timeout++;
    if (timeout >= 4999){
    return "e";
    }
  delay(1);
  }
  int i = 0;
  while (client.available()) {
    response[i++] = (char)client.read();
    if (i >= 399){
      break;
    }
  }
  char * pch;
  char * statusCode;
  int j = 0;
  pch = strtok (response, " ");
  while (pch != NULL) {
    if (j == 1 ) {
    statusCode = pch;
    }
  pch = strtok (NULL, " ");
  j++;
  }
  free(response);
  return statusCode;
}
