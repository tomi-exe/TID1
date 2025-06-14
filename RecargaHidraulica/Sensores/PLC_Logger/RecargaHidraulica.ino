/*  Servicios e Inversiones GAEA EIRL 
 *  Medición de humedad en 3 sensores y almacenamiento de datos
 *  en una tarjeta SD. Usa un RTC para la contabilización del tiempo.
 *  
 *  Los sensores de humedad sufren por electrólisis al estar, 
 *  permanentemente energizados, además de consumir corriente,
 *  por lo que son energizados mediante los pines digitales al
 *  momento de realizar las mediciones.
 *  
 *  Los sensores deben ser calibrados al 100% de humedad. Los valores
 *  de referencia se ingresan en el sistema central.
 *  
 *  Con leds deshabilitados, consumo promedio en hibernacion de 20mAh
 *  
 *  Cre. 20180105
 *  Rev. 2018013001
 */

// Base
#include <avr/wdt.h>
#include <LowPower.h>

// RTC
#include <Wire.h>
#include <RTClib.h>

// SD
#include <SPI.h>
#include <SD.h>

// Configuración sensor (debe corresponder a Intranet)
#define DATA_ID          "1100" // Que sensor somos
#define DATA_PASS        "Qrt162_7"
#define FRECUENCIA_DEF   3600 // Cada cuanto reportamos en segundos (frecuencia%8=0), 1 hora por defecto (3600s)


// Parámetros (no modificar)
#define PINPWR_HUM1     5 // Encendido sensor 30cm
#define PINPWR_HUM2     4 // Encendido sensor 60cm
#define PINPWR_HUM3     3 // Encendido sensor 90cm

#define PINDAT_HUM1     A0 // Lectura humedad 30cm
#define PINDAT_HUM2     A1 // Lectura humedad 60cm
#define PINDAT_HUM3     A2 // Lectura humedad 90cm
#define CS              8 // Chip select tarjeta SD Olimex


// Variables globales
int humedad1,humedad2,humedad3; // Variable humedad
int hibernar; // Tiempo para "hibernar" (al comienzo siempre igual a frecuencia)
RTC_DS3231 rtc; // Reloj


// Parámetros iniciales sistema
void setup() {
  wdt_disable(); // WDT off
  
  Serial.begin(9600);
  delay(3000);

  // Inicializamos el reloj
  Serial.print("Iniciando RTC... ");
  if(!rtc.begin()) {
    Serial.println(F("RTC no disponible... reiniciando..."));
    reiniciar();
  }

  // Si se ha quedado sin poder, pondemos la hora de la compilación
  // como fecha/hora inicial (carga de programa)
  if(rtc.lostPower()) {
    Serial.println("Perdida de poder, reseteando reloj...");
    rtc.adjust(DateTime(F(__DATE__), F(__TIME__)));
  }
  Serial.println("OK");

  // Inicializamos la tarjeta SD
  Serial.print("Iniciando SD...");
  if(!SD.begin(CS)) {
    Serial.println("Tarjeta no disponible o con error... reiniciar...");
    reiniciar();
  }
  Serial.println("OK");

  // Configuración pines para encendido modem y sensores
  pinMode(PINPWR_HUM1,OUTPUT);
  pinMode(PINPWR_HUM2,OUTPUT);
  pinMode(PINPWR_HUM3,OUTPUT);

  // Tiempo de hibernación en lowpower (entre mediciones y transmisiones)
  hibernar=FRECUENCIA_DEF;
  
  // Valores iniciales sensores
  humedad1=0;
  humedad2=0;
  humedad3=0;

  // Watchdog a 8S
  wdt_enable(WDTO_8S);
}


// Permite reiniciar el Arduino aprovechando el Watchdog
void reiniciar() {
    Serial.println("Reiniciando");
    wdt_enable(WDTO_15MS);
    while(1) {
    }
}


// Le da voltaje a pinPwr para energizar el sensor
// de humedad, espera 20ms para estabilizar el sensor
// y realiza una lectura en pinSensor
// Apaga el sensor, LOW en pinPwr y retorna el valor
// de la lectura
int leerSensor(int pinPwr,int pinSensor) {
  int valor=0;
  
  digitalWrite(pinPwr,HIGH);
  delay(20);
  valor=analogRead(pinSensor);
  digitalWrite(pinPwr,LOW);

  wdt_reset();
  return(valor);
}


// Obtenemos los valores de los sensores
// Esperamos un rato a que se normalicen
void actualizarVariables() {
  humedad1=leerSensor(PINPWR_HUM1,PINDAT_HUM1);
  humedad2=leerSensor(PINPWR_HUM2,PINDAT_HUM2);
  humedad3=leerSensor(PINPWR_HUM3,PINDAT_HUM3);
}


// Guarda una cadena en la tarjeta SD
void guardarSD(char *data) {
  File fout=SD.open("DATOS.TXT",FILE_WRITE);

  if(fout) {
    fout.println(data);
    fout.close();
    Serial.println(data);
  } else {
    Serial.println("Error con archivo de datos");
  }
}


// Función principal
void loop() {
  // Definimos e inicializamos arreglo para datos
  char data[60]; // Cierta holgura
  for(int i=0; i<60; i++) data[i]='\0';

  // Obtenemos valores actualizados de humedad
  Serial.print("Actualizando variables...");
  actualizarVariables();
  Serial.println(" OK");
  
  // Obtenemos la hora de día
  DateTime ahora=rtc.now();

  // Preparamos y guardamos los datos
  sprintf(data,"%d-%d-%d %d:%d:%d;%s;%s;%d,%d,%d",ahora.year(),ahora.month(),ahora.day(),ahora.hour(),ahora.minute(),ahora.second(),DATA_ID,DATA_PASS,humedad1,humedad2,humedad3);
  guardarSD(data);
  delay(1000);
  
  // Dormimos por "hibernar" segundos y repetimos
  for(int i=0; i<hibernar/8; i++) {
    LowPower.powerDown(SLEEP_8S,ADC_OFF,BOD_OFF); // Lo apagamos for real
  }
  wdt_reset();
}
