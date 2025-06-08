#!/bin/bash

# Configuration des paramètres
BROKER="mqtt.iut-blagnac.fr"  # Remplace par l'adresse de ton broker MQTT (par exemple localhost)
TOPIC="AM107/by-room/+/data"  # Remplace par le topic de tes capteurs
USER="mzerrouki"  # Utilisateur MySQL
PASS="passroot"  # Mot de passe MySQL
DB="sae23"  # Base de données

# Fonction pour insérer les données dans la base de données
insert_data() {
    local sensor_value=$1
    local sensor_type=$2
    local room=$3

    # Récupérer l'ID du capteur depuis la table Capteur
    local capteur_id=$(mysql -u "$USER" -p"$PASS" "$DB" -N -e "SELECT id_capteur FROM Capteur WHERE salle_nom='$room' AND type='$sensor_type' LIMIT 1;")
   
    # Si le capteur existe, insérer les données
    if [ -n "$capteur_id" ]; then
        # Obtenir la date et l'heure actuelles
        local current_date=$(date +%F)
        local current_time=$(date +%T)
       
        # Insérer dans la table Mesure
        mysql -u "$USER" -p"$PASS" "$DB" <<EOF
INSERT INTO Mesure (valeur, date, heure, id_capteur)
VALUES ($sensor_value, '$current_date', '$current_time', $capteur_id);
EOF
    else
        echo "Capteur pour la salle '$room' et le type '$sensor_type' non trouvé."
    fi
}

# Fonction pour écouter le topic et insérer les données
mqtt_listener() {
    mosquitto_sub -h "$BROKER" -t "$TOPIC" | while read -r message
    do
        # Extraction des informations depuis le message JSON en utilisant grep et sed
        room=$(echo "$message" | grep -oP '"room":\s*"\K[^"]+')
        temperature=$(echo "$message" | grep -oP '"temperature":\{"value":\K[^}]+')
        humidity=$(echo "$message" | grep -oP '"humidity":\{"value":\K[^}]+')
        co2=$(echo "$message" | grep -oP '"co2":\{"value":\K[^}]+')
        luminosity=$(echo "$message" | grep -oP '"illumination":\{"value":\K[^}]+')

        # Insérer les données de chaque capteur
        if [ -n "$temperature" ]; then
            insert_data "$temperature" "Température" "$room"
        fi
        if [ -n "$humidity" ]; then
            insert_data "$humidity" "Humidité" "$room"
        fi
        if [ -n "$co2" ]; then
            insert_data "$co2" "co2" "$room"
        fi
        if [ -n "$luminosity" ]; then
            insert_data "$luminosity" "Luminosité" "$room"
        fi
    done
}

# Lancer la fonction pour écouter les capteurs et insérer les données
mqtt_listener
