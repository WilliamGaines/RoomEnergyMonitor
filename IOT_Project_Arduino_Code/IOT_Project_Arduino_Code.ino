//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
// GLOBALS

// REQUIRED LIBRARIES
#include <WiFiNINA.h>
#include <ThingSpeak.h>
//-------------------------------------------------------------------------------------------------------------------------------------------------
// SETTING PINS
const int Pin_Button = 3;
const int Pin_Temperature = A0;
const int Pin_Light = A1;
const int Pin_LED = 7;
//-------------------------------------------------------------------------------------------------------------------------------------------------
// MISC GLOBAL VARIABLES
bool stopReading = false;                               // False means code reads by default.
int i = 1;                                              // Used as counter within void loop().
float temperatureReadingSum = 0.0;                      // Used to sum readings when average temperature before writing to thingspeak.
int lightReadingSum = 0;                                // Used to sum readings when average light levels before writing to thingspeak.
int ReadingsCount = 0;                                  // Counts how many readings are summed when calculating averages to write to thingspeak.
//-------------------------------------------------------------------------------------------------------------------------------------------------
// THINGSPEAK CONNECTION VARIABLES
int myChannelNumber = 2384242;                          // Channel number.
const char *myWriteAPIKey = "429ROP59X86J6ITL";         // Write API key.
//-------------------------------------------------------------------------------------------------------------------------------------------------
// WIFI CONNECTION VARIABLES
// ---
// char ssid[] = "TALKTALK7B22B9";                      // SSID 1.
// char pass[] = "8M4KKTEM";                            // Password 1.
// char ssid[] = "BT-JMASS9";                           // SSID 2.
// char pass[] = "LcMg7gNkbEa93y";                      // Password 2.
char ssid[] = "BT-3SC2NM";                              // SSID 3.
char pass[] = "vhaYVugE6LvrJr";

// ---
WiFiClient client;                                      // Client object for WiFi connection.
//-------------------------------------------------------------------------------------------------------------------------------------------------
// FUNCTION DECLARATIONS
float AnalogToCelsius(int sensorPin);                   // Function converts thermistor's analog output to Celsius using Steinhart-Hart equation.
//-------------------------------------------------------------------------------------------------------------------------------------------------



//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
// SETUP

void setup() {
  Serial.begin(9600);                                   // Initialize serial communication at 9600.
// ---
  pinMode(Pin_Button, INPUT);                           // Initialize button.
  pinMode(Pin_LED, OUTPUT);                             // Initialize LED.
//-------------------------------------------------------------------------------------------------------------------------------------------------
// CREATE WIFI SESSION
  int WifiStatus = WiFi.begin(ssid, pass);              // Attempt to connect to the internet. Creates variable for connection status.
  while (WifiStatus != WL_CONNECTED) {                  // Loop if connection attempt fails. 
    delay(1000);                                        // Wait 1 second
    Serial.println("Connecting to WiFi...");
    WifiStatus = WiFi.begin(ssid, pass);                // Try to connect again. Store the status code in "status" variable.
  }
  Serial.println("Connected to WiFi.");                 // Prints success message when successful.
//-------------------------------------------------------------------------------------------------------------------------------------------------
// CREATE THINGSPEAK SESSION
  ThingSpeak.begin(client);                             // Connect with ThingSpeak.
}
//-------------------------------------------------------------------------------------------------------------------------------------------------



//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
// LOOP

//-------------------------------------------------------------------------------------------------------------------------------------------------
// CHANGE BUTTON INTO SWITCH
void loop() {
  int buttonState = digitalRead(Pin_Button);            // Read the button state.
// ---
  if (buttonState == HIGH) {                            // If the button is pressed,
    stopReading = !stopReading;                         // Flip the boolean variable.
    digitalWrite(Pin_LED, stopReading ? HIGH : LOW);    // Turn LED on or off based on the boolean variable.
  }
//-------------------------------------------------------------------------------------------------------------------------------------------------
// DATA LOADING FOR BUTTON - THINGSPEAK
  ThingSpeak.setField(3, stopReading ? 1 : 0);          // StopReading state prepared for write. This data will indicate when the room is engaged(When the sensors are not reading).
//-------------------------------------------------------------------------------------------------------------------------------------------------
// TEMPERATURE AND LIGHT LEVEL SENSOR READINGS
  if (!stopReading) {                                                 // While stopReading boolean is false, execute code below.
    float temperatureCelsius = AnalogToCelsius(Pin_Temperature);      // Call function, returns temperature reading in Celsius.
    int lightLevel = analogRead(Pin_Light);                           // Reads the light level.
// ---
// DISPLAY READINGS
    Serial.print("Temperature: ");                      // Print readings:
    Serial.print(temperatureCelsius);                   // -
    Serial.print(" C | Light Level: ");                 // -
    Serial.println(lightLevel);                         // -
// ---
// AVERAGE READINGS
    temperatureReadingSum += temperatureCelsius;        // Add current temperature reading to the sum of readings (Used to calcualte average).
    lightReadingSum += lightLevel;                      // Add current light level reading to the sum of readings (Used to calcualte average).
    ReadingsCount++;                                    // Counts how many readings are in the ReadingSum variables (Used to calculate average).
//---
// DATA LOADING FOR TEMPERATURE & LIGHTLEVEL - THINGSPEAK
    ThingSpeak.setField(1, temperatureReadingSum/ReadingsCount);       // divide temperatureReadingSum by the ReadingsCount for average of temperature readings. Set thingspeak temperature field to prepare for write.
    ThingSpeak.setField(2, lightReadingSum/ReadingsCount);             // divide lightReadingSum by the ReadingsCount for average of temperature readings. Set thingspeak light level field to prepare for write.
  }
// ---
// WRITE TO THINGSPEAK
  if (i >= 6) {                                                                 // Ensures that 15 seconds have passed (minimum update time for ThingSpeak)(6*2.5 seconds for each loop).
    int writeStatus = ThingSpeak.writeFields(myChannelNumber, myWriteAPIKey);   // Write the set fields to ThingSpeak, creates a variable for write status.
    i = 0;                                                                      // Reset the counter, ensuring 15 seconds before if condition will be met again.
    ReadingsCount = 0;                                                          // reset the count of reading, ready to calculate the average for the next set of readings.
    temperatureReadingSum = 0;                                                  // reset the sum of temperature readings, ready to calculate the average for the next set of readings.
    lightReadingSum = 0;                                                        // reset the count of light levels, ready to calculate the average for the next set of readings

    if (writeStatus == 200) {                                                   // If status indicates success,
      Serial.println("Write successful");                                       // Print success message.
    } else {                                                                    // Otherwise,
      Serial.println("Write failed. HTTP error code " + String(writeStatus));   // Print fail message.
    }
  }
//-------------------------------------------------------------------------------------------------------------------------------------------------
// END OF LOOP ACTIONS
  delay(2500);                                          // 2.5 second delay for each loop.
  i++;                                                  // increment itterations counter now loop is complete
}
//-------------------------------------------------------------------------------------------------------------------------------------------------



//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
// FUNCTIONS CODE

// Function converts thermistor's analog output to Celsius using Steinhart-Hart equation.
float AnalogToCelsius(int TempPin) {
  int temperatureAnalog = analogRead(TempPin);
  float R = 1023.0 / temperatureAnalog - 1.0;
  R = 100000 * R;
  float temperatureCelsius = 1.0 / (log(R / 100000) / 4275 + 1 / 298.15) - 273.15;
  return temperatureCelsius;
}
//-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-
