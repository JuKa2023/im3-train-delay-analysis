#!/bin/bash

# Function for logging
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1"
}

# Main loop
log "Starting update service..."

while true; do
    # Fetch train data
    TRAIN_STATUS=$(curl -s -w "%{http_code}" -o /dev/null "http://web:80/train_data_indb.php")
    if [ "$TRAIN_STATUS" = "200" ]; then
        log "Train data updated successfully"
    else
        log "Train data update failed with status: $TRAIN_STATUS"
    fi

    # Fetch weather data
    WEATHER_STATUS=$(curl -s -w "%{http_code}" -o /dev/null "http://web:80/weather_data_indb.php")
    if [ "$WEATHER_STATUS" = "200" ]; then
        log "Weather data updated successfully"
    else
        log "Weather data update failed with status: $WEATHER_STATUS"
    fi

    log "Sleeping for 2.5 minutes..."
    sleep 150
done 