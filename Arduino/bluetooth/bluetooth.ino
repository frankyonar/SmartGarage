#include <ESP32Servo.h>
#include <BluetoothSerial.h>

// Pin dei servo motori
#define SERVO1_PIN 15
#define SERVO2_PIN 2

Servo servo1;
Servo servo2;
int posVal = 0;
int posVal2 = 0;

BluetoothSerial SerialBT;

void setup() {
  Serial.begin(115200);
  SerialBT.begin("ESP32_Garage"); // Nome Bluetooth

  // Configurazione dei servo motori
  servo1.setPeriodHertz(50);
  servo2.setPeriodHertz(50);
  servo1.attach(SERVO1_PIN, 500, 2500);
  servo2.attach(SERVO2_PIN, 500, 2500);

  Serial.println("Il dispositivo è pronto per la connessione Bluetooth.");
}

void loop() {
  if (SerialBT.available()) {
    String command = SerialBT.readStringUntil('\n');
    command.trim();

    if (command.equals("apri_garage")) {
      handleApriGarage();
    } else if (command.equals("chiudi_garage")) {
      handleChiudiGarage();
    }
  }
}

void handleApriGarage() {
  while (posVal < 100 && posVal2 < 100) {
    servo1.write(100 - posVal); // Muove il servo1 in senso opposto
    servo2.write(posVal2);
    posVal++;
    posVal2++;
    delay(20);
  }
  SerialBT.println("Garage aperto");
}

void handleChiudiGarage() {
  while (posVal > 0 && posVal2 > 0) {
    servo1.write(100 - posVal); // Muove il servo1 in senso opposto
    servo2.write(posVal2);
    posVal--;
    posVal2--;
    delay(20);
  }
  SerialBT.println("Garage chiuso");
}