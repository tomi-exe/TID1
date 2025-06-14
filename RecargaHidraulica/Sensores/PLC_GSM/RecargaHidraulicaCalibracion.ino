/*  Servicios e Inversiones GAEA EIRL 
 *  Medición de humedad en 3 sensores y transmisión de datos
 *  vía 2G a un servidor remoto
 *  
 *  Los sensores de humedad sufren por electrólisis al estar, 
 *  permanentemente energizados, además de consumir corriente,
 *  por lo que son energizados mediante los pines digitales al
 *  momento de realizar las mediciones.
 *  
 *  Cre. 20171025
 *  Rev. 2017120601
 *  Rev. 2017011001
 */
#include <avr/wdt.h>
#include <SoftwareSerial.h>


#define PINPWR_HUM1     5 // Encendido sensor 30cm
#define PINPWR_HUM2     4 // Encendido sensor 60cm
#define PINPWR_HUM3     3 // Encendido sensor 90cm

#define PINDAT_HUM1     A0 // Lectura humedad 30cm
#define PINDAT_HUM2     A1 // Lectura humedad 60cm
#define PINDAT_HUM3     A2 // Lectura humedad 90cm


int humedad1,humedad2,humedad3; // Variable humedad


// Parámetros iniciales sistema
void setup() {
  wdt_disable(); // WDT off
  
  Serial.begin(19200);

  Serial.print("Iniciando... ");

  // Configuración pines para encendido modem y sensores
  pinMode(PINPWR_GSM,OUTPUT);
  pinMode(PINPWR_HUM1,OUTPUT);
  pinMode(PINPWR_HUM2,OUTPUT);
  pinMode(PINPWR_HUM3,OUTPUT);
  
  // Valores iniciales sensores
  humedad1=0;
  humedad2=0;
  humedad3=0;

  // Envio
  fallaEnvio=0;
  ultimoEnvio=0; // Para que intente enviar apenas parte

  // Watchdog a 8S
  wdt_enable(WDTO_8S);
  
  Serial.println("OK");
}


// Permite reiniciar el Arduino aprovechando el Watchdog
void reiniciar() {
    Serial.println("Reiniciando");
    wdt_enable(WDTO_15MS);
    while(1) {
    }
}


// Limpia el buffer de mensajes
void limpiarBuffer(char *dirBuff) {
  *dirBuff='\0';
}



// Le da voltaje a pinPwr para energizar el sensor
// de humedad, espera 20ms para estabilizar el sensor
// y realiza una lectura en pinSensor
// Apaga el sensor, LOW en pinPwr y retorna el valor
// de la lectura
int leerSensor(int pinPwr,int pinSensor) {
  Serial.print("Mida el voltaje en el pin ");
  Serial.println(pinPwr);
  int valor=0;
  
  digitalWrite(pinPwr,HIGH);
  delay(5000);
  wdt_reset();
  
  valor=analogRead(pinSensor);
  digitalWrite(pinPwr,LOW);
  Serial.print("Valor humedad: ");
  Serial.println(valor);
  delay(5000);
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


// Función principal
void loop() {
  actualizarVariables();


  delay(3000);
  wdt_reset();
}
