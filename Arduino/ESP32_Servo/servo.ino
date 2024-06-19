#include <ESP32Servo.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <WebServer.h>

// Credenziali WiFi
const char* ssid_router = "Hotspot 374";
const char* password_router = "123456dK";

// Indirizzo IP del server PHP
const char* serverName = "http://192.168.212.223"; // Sostituisci con l'IP del tuo server

// Pin dei servo motori
#define SERVO1_PIN 15
#define SERVO2_PIN 2

Servo servo1;
Servo servo2;
int posVal = 0;
int posVal2 = 0;

// Server web sulla porta 80
WebServer server(80);

void setup() {
  Serial.begin(115200);

  // Configurazione dei servo motori
  servo1.setPeriodHertz(50);
  servo2.setPeriodHertz(50);
  servo1.attach(SERVO1_PIN, 500, 2500);
  servo2.attach(SERVO2_PIN, 500, 2500);

  // Connessione alla rete WiFi
  WiFi.begin(ssid_router, password_router);
  Serial.println("Connessione WiFi in corso...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nConnesso alla rete WiFi");
  Serial.print("Indirizzo IP: ");
  Serial.println(WiFi.localIP());

  // Configurazione delle route del server
  server.on("/apri_garage", handleApriGarage);
  server.on("/chiudi_garage", handleChiudiGarage);

  // Avvio del server
  server.begin();
  Serial.println("Server HTTP avviato");
}

void loop() {
  server.handleClient();
}

void handleApriGarage() {
  while (posVal < 100 && posVal2 < 100) {
    servo1.write(100 - posVal); // Muove il servo1 in senso opposto
    servo2.write(posVal2);
    posVal++;
    posVal2++;
    delay(20);
  }
  inviaStatoGarage("aperta");
  server.send(200, "text/plain", "Garage aperto");
}

void handleChiudiGarage() {
  while (posVal > 0 && posVal2 > 0) {
    servo1.write(100 - posVal); // Muove il servo1 in senso opposto
    servo2.write(posVal2);
    posVal--;
    posVal2--;
    delay(20);
  }
  inviaStatoGarage("chiusa");
  server.send(200, "text/plain", "Garage chiuso");
}

void inviaStatoGarage(const char* stato) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String url = String(serverName) + "/aggiorna_stato_garage.php?stato_garage=" + stato;
    http.begin(url.c_str());
    int httpResponseCode = http.GET();
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } else {
      Serial.print("Errore nella richiesta HTTP: ");
      Serial.println(httpResponseCode);
    }
    http.end();
  }
}

