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
#include <LowPower.h>
#include <SoftwareSerial.h>

// Entel
//#define APN_SERVER       "imovil.entelpcs.cl"
//#define APN_USER         "entelpcs"
//#define APN_PASS         "entelpcs"

// Movistar
// #define APN_SERVER       "wap.tmovil.cl"
// #define APN_USER         "wap"
// #define APN_PASS         "wap"

// MCI Telecom
#define APN_SERVER       "m2m.movistar.cl"
#define APN_USER         ""
#define APN_PASS         ""

#define DATA_SERVER      "data.recargahidraulica.cl"
#define DATA_PORT        "80"
#define DATA_HOST        "data.recargahidraulica.cl"
#define DATA_URL         "/data.php"
#define DATA_ID          "1001" // Que sensor somos
#define DATA_PASS        "Qrt162_7"


#define PINPWR_GSM      9 // Encendido modem
#define PINPWR_HUM1     5 // Encendido sensor 30cm
#define PINPWR_HUM2     4 // Encendido sensor 60cm
#define PINPWR_HUM3     3 // Encendido sensor 90cm

#define PINDAT_HUM1     A0 // Lectura humedad 30cm
#define PINDAT_HUM2     A1 // Lectura humedad 60cm
#define PINDAT_HUM3     A2 // Lectura humedad 90cm

// Valores para frecuencia minima, máxima y por defecto de hibernación
#define FRECUENCIA_DEF  3600 // Cada cuanto reportamos en segundos (frecuencia%8=0), 1 hora por defecto
#define FRECUENCIA_MIN  64 // Cada 1 minuto es lo minimo que aceptamos
#define FRECUENCIA_MAX  21600 // 6 horas máximo perído entre mediciones

// IO para modem
SoftwareSerial Modem(7,8);

// Variables globales
char bufferAnswer[200]; // Mensajería
unsigned long ultimoEnvio; // Ultimo envio
int humedad1,humedad2,humedad3; // Variable humedad
int fallaEnvio; // Contador de fallas de 
int hibernar; // Tiempo para "hibernar" (al comienzo siempre igual a frecuencia)


// Parámetros iniciales sistema
void setup() {
  wdt_disable(); // WDT off
  
  Serial.begin(19200);
  Modem.begin(19200);

  Serial.print("Iniciando... ");

  // Configuración pines para encendido modem y sensores
  pinMode(PINPWR_GSM,OUTPUT);
  pinMode(PINPWR_HUM1,OUTPUT);
  pinMode(PINPWR_HUM2,OUTPUT);
  pinMode(PINPWR_HUM3,OUTPUT);

  // Tiempo de hibernación en lowpower (entre mediciones y transmisiones)
  hibernar=FRECUENCIA_DEF;
  
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


// Envía un comando y espera una respuesta ack (opcional respuesta) por un tiempo determinado
boolean enviarComando(char *comando,char *ack,unsigned long tiempo, boolean respuesta=true) {
  char *pTemptBuff=bufferAnswer;
  unsigned long timeOut=millis()+tiempo;
  boolean rtn=false;
  
  Modem.flush();
  Modem.println(comando); 

  if(respuesta) {
    do {
      if(Modem.available()>0) {
        char dataIn=Modem.read();
        Serial.print(dataIn);
        if(pTemptBuff<(bufferAnswer+sizeof(bufferAnswer))-1) {
          *(pTemptBuff++)=dataIn;
          *pTemptBuff='\0';
          timeOut=millis()+tiempo;
          if(strstr(bufferAnswer,ack)) return(true);
        } else {
          pTemptBuff=bufferAnswer;
        }
      }
      wdt_reset();
    } while(timeOut>millis());
    
    if(timeOut<millis()) Serial.println("Timeout");
  } else return(true);
  return(false);
}


// Establece una conexión TCP con el servidor
int conectar() {
  int status=0;
  
  // OK
  if(enviarComando("AT","OK",5000)) {
    limpiarBuffer(bufferAnswer);

    // Conectamos GPRS
    if(enviarComando("AT+CGATT=1","OK",10000)) {
      limpiarBuffer(bufferAnswer);
      
      // Apagar cualquier sesión IP (no funciona de otro modo)
      if(enviarComando("AT+CIPSHUT","SHUT OK",10000)) {
        limpiarBuffer(bufferAnswer);
        
        // Conexión unica
        if(enviarComando("AT+CIPMUX=0","OK",10000)) {
          limpiarBuffer(bufferAnswer);
          
          // Configuramos APN, usuario y clave
          char apn[200];
          sprintf(apn,"AT+CSTT=\"%s\",\"%s\",\"%s\"",APN_SERVER,APN_USER,APN_PASS);
          if(enviarComando(apn,"OK",10000)) {
            limpiarBuffer(bufferAnswer);
            
            // Esperamos IP START
            if(enviarComando("AT+CIPSTATUS","START",10000)) {
              limpiarBuffer(bufferAnswer);
              
              // Encendemos la conexión inalámbrica
              if(enviarComando("AT+CIICR","OK",10000)) {
                limpiarBuffer(bufferAnswer);
                
                // Tenemos IP?
                if(enviarComando("AT+CIPSTATUS","IP GPRSACT",10000)) {
                  limpiarBuffer(bufferAnswer);
                  
                  // Asignamos la IP
                  if(enviarComando("AT+CIFSR",".",10000)) {
                    limpiarBuffer(bufferAnswer);
                    
                    // Establecemos la conexión TCP
                    char cmd[200];
                    sprintf(cmd,"AT+CIPSTART=\"TCP\",\"%s\",\"%s\"",DATA_SERVER,DATA_PORT);
                    if(enviarComando(cmd,"CONNECT OK",30000)) {
                      limpiarBuffer(bufferAnswer);
                      
                    } else status=10;
                  } else status=9;
                } else status=8;
              } else status=7;
            } else status=6;
          } else status=5;
        } else status=4;
      } else status=3;
    } else status=2;
  } else status=1;
  
  return(status);
}


// Termina la conexión TCP
int desconectar() {
  int status=0;
  Serial.print("Desconectando... ");
  if("AT+CIPCLOSE=1","OK",10000) {
    if("AT+CIPSHUT","OK",10000) {
      Serial.println("OK");
    } else status=2;
  } else status=1;

  return(status);
}


// Apaga el modem de datos
boolean apagarModem() {
  digitalWrite(PINPWR_GSM,LOW);
  delay(1000);
  wdt_reset();
  digitalWrite(PINPWR_GSM,HIGH);
  delay(2000);
  wdt_reset();
  digitalWrite(PINPWR_GSM,LOW);
  delay(3000);
  wdt_reset();
  
  return(true);
}


// Enciende el modem
boolean encenderModem() {
  int intentos=0;
  boolean on=false;
  on=enviarComando("AT","OK",2000);

  if(!on) {
    digitalWrite(PINPWR_GSM,HIGH);
    delay(3000);
    digitalWrite(PINPWR_GSM,LOW);
  }

  while(!on) {
    on=enviarComando("AT","OK",2000);  
  }

  do {
    intentos++;
    if(intentos>30) reiniciar();
  } while(!enviarComando("AT+CGREG?","+CGREG: 0,1",2000) && !enviarComando("AT+CGREG?","+CGREG: 0,5",2000));
  
  return(true);
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


// Espera datos de los buffers
boolean esperarDatos(int tiempo, char *ack) {
  char *pTemptBuff=bufferAnswer;
  unsigned long timeOut=millis()+tiempo;
  
  do {
    if(Modem.available()>0) {
      char dataIn=Modem.read();
      Serial.print(dataIn);
      if(pTemptBuff<(bufferAnswer+sizeof(bufferAnswer))-1) {
        *(pTemptBuff++)=dataIn;
        *pTemptBuff='\0';
        timeOut=millis()+tiempo;
        if(strstr(bufferAnswer,ack)) return(true);
      } else {
        pTemptBuff=bufferAnswer;
      }
    }
    wdt_reset();
  } while(timeOut>millis());
  
  if(timeOut<millis()) Serial.println("Timeout");
  return(false);
}

void procesarRespuesta() {
  char comando[20];
  for(int i=0; i<20; i++) comando[i]='\0';
  
  int inicio=false;
  int pos=0;
  
  for(int i=0; i<sizeof(bufferAnswer)-1; i++) {
    char c=bufferAnswer[i];
    if(c=='$' && inicio==false) inicio=true;

    // Nos saltamos el $ inicial y nos comemos el ! final
    if(inicio && c!='$' && c!='!') {
      comando[pos]=c;
      pos++;
    }
  }
  int nuevoTiempo=atoi(comando);
  if(nuevoTiempo>=0 && FRECUENCIA_MAX<=21600) hibernar=nuevoTiempo;  
}


// Función principal
void loop() {
  // Si el contador de falla es superior a 3, reiniciamos
  if(fallaEnvio>3) {
    reiniciar();
  }
  
  // Encendemos el modem y transmitimos los valores de humedad
  Serial.println("Iniciando modem...");
  if(encenderModem()) {
    Serial.println("Actualizando variables...");
    actualizarVariables(); // Obtenemos valores actualizados de humedad
    
    Serial.println("Conectando...");
    int st=conectar();
    if(st==0) {
      Serial.println("Enviando...");
      char tamano[50];
      char data[250];
      char valores[15];
      sprintf(valores,"%d,%d,%d",humedad1,humedad2,humedad3);
      sprintf(data,"GET %s?i=%s&c=%s&d=%s HTTP/1.1\r\nHost: %s\r\nConnection: close\r\n\r\n",DATA_URL,DATA_ID,DATA_PASS,valores,DATA_HOST);

      // Enviamos tamaño solicitud
      sprintf(tamano,"AT+CIPSEND=%d",strlen(data));
      if(enviarComando(tamano,">",15000)) {
        limpiarBuffer(bufferAnswer);
        // Enviamos solicitud GET
        if(enviarComando(data,"SEND OK",15000)) {
          limpiarBuffer(bufferAnswer);
          if(esperarDatos(30000,"CLOSED")) {
            procesarRespuesta();
          }
          limpiarBuffer(bufferAnswer);
        } else fallaEnvio++;
      }

      desconectar();
      ultimoEnvio=millis();
      fallaEnvio=0;
    } else {
      fallaEnvio++;
    }
    Serial.print("Apagando modem...");
    if(apagarModem()) Serial.println(" OK");
  } else Serial.println("No se pudo encender modem");

  // Dormimos por hibernar segundos y repetimos
  for(int i=0; i<hibernar/8; i++) {
    LowPower.powerDown(SLEEP_8S,ADC_OFF,BOD_OFF); // Lo apagamos for real
  }
  wdt_reset();
}
