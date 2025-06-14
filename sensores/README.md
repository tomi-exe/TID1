# Proyecto Sensores con Arduino y MongoDB

Este proyecto muestra cómo usar placas **Arduino** para medir variables de humedad en sensores y almacenar los datos en una base de datos **MongoDB**. Aprovecharemos la versatilidad de Arduino para la adquisición de datos y la escalabilidad de MongoDB para su almacenamiento y consulta.

## Características

- Lectura de 3 sensores de humedad analógica.
- Comunicación serial entre Arduino y un microservicio (Node.js/Python).
- Envío de datos en formato JSON a un servidor REST.
- Almacenamiento en MongoDB para consultas flexibles y escalables.
- Dashboard web (opcional) para visualizar series de tiempo.

## Requisitos

### Hardware

- Placa Arduino (Uno, Nano, etc.)
- 3 sensores de humedad con alimentación controlada (p. ej. sensores de resistencia).
- Cables y resistencias de precisión.
- Módulo serial o Ethernet/Wi-Fi (opcional).

### Software

- IDE de Arduino (v1.8+).
- Node.js (v12+) o Python 3.x para el microservicio REST.
- MongoDB Community Server (v4+).
- Librería [`ArduinoJson`](https://arduinojson.org/) para formateo de JSON.

## Instalación

1. Clona este repositorio:
   ```bash
   git clone https://github.com/tu-usuario/proyecto-sensores.git
   cd proyecto-sensores
   ```
2. Sube el sketch de Arduino:
   - Abre `Sensores/PLC_GSM/RecargaHidraulica.ino` en el IDE de Arduino.
   - Ajusta los pines y credenciales de red si usas un módulo GPRS/Wi-Fi.
   - Compila y carga en tu placa.
3. Configura MongoDB:
   - Inicia el servicio de MongoDB (local o remoto).
   - Crea la base de datos `sensores` y la colección `datos`.
4. Levanta el microservicio REST (ejemplo Node.js):
   ```bash
   cd backend
   npm install
   npm start
   ```
   Asegúrate de configurar la URI de MongoDB en `backend/.env`.

## Uso

1. Arduino lee los sensores y envía paquetes JSON como:
   ```json
   {
     "sensorId": "1001",
     "fecha": "2023-07-15T14:30:00Z",
     "humedad": [450, 480, 500]
   }
   ```
2. El microservicio recibe el POST en `/api/datos` y guarda el documento en MongoDB.
3. Visualiza los datos con tu cliente favorito (Mongo Shell, Compass o un dashboard web).

## Estructura de carpetas

- `Sensores/PLC_GSM/RecargaHidraulica.ino` – Sketch principal de Arduino.
- `backend/` – Código del servicio REST que conecta a MongoDB.
- `docs/` – Diagramas y guías adicionales.

## Contribuciones

¡Todas las mejoras y sugerencias son bienvenidas! Lee la sección [Contributing](docs/CONTRIBUTING.md) antes de enviar un pull request.

## Licencia

Este proyecto está bajo la licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
