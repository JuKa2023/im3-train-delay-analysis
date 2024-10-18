erDiagram
    weather_data {
        VARCHAR location
        TIMESTAMP timestamp
        FLOAT temperature_celsius
        FLOAT wind_speed
        FLOAT rain
        INT weather_code
        FLOAT showers
        FLOAT snowfall
        FLOAT wind_gusts_10m
    }
    
    stationtable {
        INT id PK
        VARCHAR destination
        VARCHAR departure
        INT departure_time_stamp
        DATETIME departure_time
        INT delay
        VARCHAR platform
    }
