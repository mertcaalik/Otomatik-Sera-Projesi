#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <Arduino_JSON.h>
#include "DHT.h"
#define soilMoisturePin A0
#define DHTPIN D1
#define DHTTYPE DHT11
#define FAN_PIN D0
#define SU_PIN D2 // Fanın bağlı olduğu pin
DHT dht11_sensor(DHTPIN, DHTTYPE);

const char* ssid = "ali.";
const char* password = "galaxya3456";

String postData = "";
String payload = "";

float send_Temp;
int send_Humd;
int soilMoistureValue;
int soilMoisturePercentage;
int minSensorValue = 0;  // Minimum ölçüm değeri
int maxSensorValue = 1023;
String send_Status_Read_DHT11 = "";

void get_DHT11_sensor_data() {
  Serial.println();
  Serial.println("-------------get_DHT11_sensor_data()");
  
  send_Temp = dht11_sensor.readTemperature();
  send_Humd = dht11_sensor.readHumidity();
  
  if (isnan(send_Temp) || isnan(send_Humd)) {
    Serial.println("Failed to read from DHT sensor!");
    send_Temp = 0.00;
    send_Humd = 0;
    send_Status_Read_DHT11 = "FAILED";
  } else {
    send_Status_Read_DHT11 = "SUCCEED";
  }
  
  Serial.printf("Temperature : %.2f °C\n", send_Temp);
  Serial.printf("Humidity : %d %%\n", send_Humd);
  Serial.printf("Status Read DHT11 Sensor : %s\n", send_Status_Read_DHT11);
  Serial.println("-------------");
}

// Fonksiyon: Analog değeri yüzde olarak dönüştürür
int mapToPercentage(int sensorValue, int minSensorValue, int maxSensorValue) {
  return map(sensorValue, minSensorValue, maxSensorValue, 0, 100);
}

void get_Soil_Moisture_data() {
  Serial.println();
  Serial.println("-------------get_Soil_Moisture_data()");
  
  soilMoistureValue = analogRead(soilMoisturePin);
  soilMoisturePercentage = mapToPercentage(soilMoistureValue, minSensorValue, maxSensorValue);
  
  Serial.print("Soil Moisture Value: ");
  Serial.print(soilMoistureValue);
  Serial.print(" (Analog) | ");
  Serial.print(soilMoisturePercentage);
  Serial.println("% (Percentage)");
  Serial.println("-------------");
}

void check_and_control_su() {
  HTTPClient http;
  WiFiClient client;
  http.begin(client, "http://192.168.150.104/seraprojesi/sureferans_degeri.php");
  int httpCode = http.GET();
  
  if (httpCode > 0) {
    String refValue = http.getString();
    Serial.println("Referans Değeri Alındı: " + refValue);

    // JSON verisini işle
    JSONVar referenceValue = JSON.parse(refValue);
    
    // Referans değeri doğru şekilde alındı mı?
    if (JSON.typeof(referenceValue) == "undefined") {
      Serial.println("Hata: JSON verisi işlenemedi!");
      return;
    }

    int refValueInt = referenceValue["referans_sayisi"];

    // Sıcaklık değeri ile referans değeri karşılaştırması
    if (soilMoisturePercentage > refValueInt) {
      Serial.println("Toprak Nemi Referans Değerini Geçti, Su Pompası Çalıştırılıyor.");
      digitalWrite(SU_PIN, LOW); // Fanı aç
    } else {
      Serial.println("Toprak Nemi Referans Değerini Geçmedi, Su Pompası Kapatılıyor.");
      digitalWrite(SU_PIN, HIGH); // Fanı kapat
    }
  } else {
    Serial.println("Hata: Referans Değeri Alınamadı!");
  }

  http.end();
}

void check_and_control_fan() {
  HTTPClient http;
  WiFiClient client;
  http.begin(client, "http://192.168.150.104/seraprojesi/referans_degeri.php");
  int httpCode = http.GET();
  
  if (httpCode > 0) {
    String refValue = http.getString();
    Serial.println("Referans Değeri Alındı: " + refValue);

    // JSON verisini işle
    JSONVar referenceValue = JSON.parse(refValue);
    
    // Referans değeri doğru şekilde alındı mı?
    if (JSON.typeof(referenceValue) == "undefined") {
      Serial.println("Hata: JSON verisi işlenemedi!");
      return;
    }

    int refValueInt = referenceValue["referans_sayisi"];

    // Sıcaklık değeri ile referans değeri karşılaştırması
    if (send_Temp > refValueInt) {
      Serial.println("Sıcaklık Referans Değerini Geçti, Fan Çalıştırılıyor.");
      digitalWrite(FAN_PIN, LOW); // Fanı aç
    } else {
      Serial.println("Sıcaklık Referans Değerini Geçmedi, Fan Kapatılıyor.");
      digitalWrite(FAN_PIN, HIGH); // Fanı kapat
    }
  } else {
    Serial.println("Hata: Referans Değeri Alınamadı!");
  }

  http.end();
}



void setup() {
  Serial.begin(115200);
  delay(2000);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.println();
  Serial.println("-------------");
  Serial.print("Connecting");

  int connecting_process_timed_out = 20;
  connecting_process_timed_out = connecting_process_timed_out * 2;
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print(".");

    delay(250);

    delay(250);
    if(connecting_process_timed_out > 0) connecting_process_timed_out--;
    if(connecting_process_timed_out == 0) {
      delay(1000);
      ESP.restart();
    }
  }

  pinMode(SU_PIN, OUTPUT);
  digitalWrite(SU_PIN, HIGH);
  
  pinMode(FAN_PIN, OUTPUT);
  digitalWrite(FAN_PIN, HIGH); // Fan pinini çıkış olarak ayarla

  Serial.println();
  Serial.print("Successfully connected to : ");
  Serial.println(ssid);
  Serial.println("-------------");

  dht11_sensor.begin();

  delay(2000);
}

void loop() {
  if(WiFi.status()== WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;
    int httpCode;

    postData = "id=1";
    payload = "";
    Serial.println();
    Serial.println("---------------topraknem.php");
    http.begin(client, "http://192.168.150.104/seraprojesi/topraknem.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    httpCode = http.POST(postData);
    payload = http.getString();
  
    Serial.print("httpCode : ");
    Serial.println(httpCode);
    Serial.print("payload  : ");
    Serial.println(payload);
    
    http.end();
    Serial.println("---------------");

    delay(1000);

    get_Soil_Moisture_data();

    // Fan kontrolü

    delay(5000); // 5 saniye bekleyin



    postData = "id=esp32_01";
    postData += "&temperature=" + String(send_Temp);
    postData += "&humidity=" + String(send_Humd);
    postData += "&status_read_sensor_dht11=" + send_Status_Read_DHT11;
    postData += "&soil_moisture=" + String(soilMoisturePercentage);
    payload = "";
  
    Serial.println();
    Serial.println("---------------verigönder.php");
    http.begin(client, "http://192.168.150.104/seraprojesi/verigönder.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
   
    httpCode = http.POST(postData);
    payload = http.getString();
  
    Serial.print("httpCode : ");
    Serial.println(httpCode);
    Serial.print("payload  : ");
    Serial.println(payload);
    
    http.end();
    Serial.println("---------------");

    postData = "id=esp32_01";
    payload = "";
    Serial.println();
    Serial.println("---------------verial.php");
    http.begin(client, "http://192.168.150.104/seraprojesi/verial.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
   
    httpCode = http.POST(postData);
    payload = http.getString();
  
    Serial.print("httpCode : ");
    Serial.println(httpCode);
    Serial.print("payload  : ");
    Serial.println(payload);
    
    http.end();
    Serial.println("---------------");

    delay(1000);

    get_DHT11_sensor_data();
    check_and_control_fan();
    check_and_control_su();
    
    delay(4000);
  }
}
