#include <WiFi.h>
#include <HTTPClient.h>




#include <WebServer.h>



#include "DHT.h"

#define DHTPIN 13
#define DHTTYPE DHT11

#define LUCE_PIN 15

const char* ssid = "Hotspot 374";
const char* password = "123456dK";
const char* serverName = "http://192.168.212.223/save_data.php"; // IP del computer

WebServer server(80);
DHT dht(DHTPIN, DHTTYPE);

String stato_luce = "OFF"; // Dichiarazione della variabile stato_luce

void setup() {
  Serial.begin(115200);
  setup_wifi();

  server.on("/luce", handleLuce);
  server.on("/dati_sensori", handleDatiSensori);
  server.on("/stato", handleStato);

  server.begin();

  pinMode(LUCE_PIN, OUTPUT);
  dht.begin();
}

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP()); // Stampa l'indirizzo IP della ESP32
}

void handleLuce() {
  String cmd = server.arg("cmd");
  if (cmd == "accendi") {
    digitalWrite(LUCE_PIN, HIGH);
    stato_luce = "ON";
    server.send(200, "text/plain", "Luce accesa");
  } else if (cmd == "spegni") {
    digitalWrite(LUCE_PIN, LOW);
    stato_luce = "OFF";
    server.send(200, "text/plain", "Luce spenta");
  } else {
    server.send(400, "text/plain", "Comando non valido");
  }
}

void handleDatiSensori() {
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  if (isnan(h) || isnan(t)) {
    server.send(500, "text/plain", "Errore nella lettura del sensore DHT");
    return;
  }
  String data = "Temperatura: " + String(t) + "°C, Umidità: " + String(h) + "%";
  server.send(200, "text/plain", data);
  sendDataToServer(t, h);
}

void handleStato() {
  String stato = "{\"luce\":\"" + stato_luce + "\"}";
  server.send(200, "application/json", stato);
}

void sendDataToServer(float temperatura, float umidita) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String serverPath = String(serverName) + "?temperatura=" + String(temperatura) + "&umidita=" + String(umidita);
    
    http.begin(serverPath.c_str());
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
  } else {
    Serial.println("WiFi Disconnesso");
  }
}

void loop() {
  server.handleClient();
}
