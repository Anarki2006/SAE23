# SAÉ23 - Supervision IoT de bâtiments en BUT Réseaux & Télécommunications

## 📌 Description
Ce projet a été réalisé dans le cadre de la SAÉ23 en BUT Réseaux & Télécommunications. Il consiste à mettre en place une solution de supervision de capteurs IoT (température, humidité, CO2, luminosité) dans plusieurs salles réparties dans différents bâtiments.

Les données sont récupérées depuis un broker MQTT (`mqtt.iut-blagnac.fr`), traitées avec Node-RED, stockées dans une base de données MySQL via InfluxDB et affichées via un site web dynamique PHP couplé à Grafana pour la visualisation en temps réel.

---

## 🧰 Technologies utilisées

- 🔧 **Docker** (containers pour chaque service)
- 🌐 **MQTT** (protocole IoT léger)
- ⚙️ **Node-RED** (gestion des flux de capteurs)
- 💾 **MySQL + phpMyAdmin**
- 📊 **Grafana** (tableaux de bord en temps réel)
- 💻 **PHP / HTML / CSS** (site web dynamique)
- 🐧 **Lubuntu** (VM Linux)
- 🐙 **Git / GitHub** (versionning)

---

## ⚙️ Architecture du projet

```text
Capteurs MQTT --> Broker MQTT --> Node-RED --> MySQL --> Site Web (PHP) / Grafana
                                        |
                                    Docker Containers
```

---

## 📁 Structure des dossiers

```
SAE23/
│
├── site/                 # Site web PHP + fichiers HTML/CSS
│   ├── index.php
│   └── login.php
│
├── script/
│   └── script.sh         # Script bash de récupération et insertion MQTT
│
├── bdd/
│   └── sae23.sql         # Export de la base de données MySQL
│
├── dashboard/
│   └── grafana.json      # Export du dashboard Grafana
│
├── images/               # Captures ou schémas
│
└── README.md             # Ce fichier
```

---

## ✅ Fonctionnalités

- 🔐 Authentification des utilisateurs (administrateurs & gestionnaires)
- 📥 Insertion automatique des mesures depuis MQTT toutes les 3 minutes
- 📈 Affichage des courbes et valeurs en temps réel avec Grafana
- 🧭 Navigation par bâtiment / salle
- 🗃️ Interface de supervision dynamique (PHP + MySQL)

---

## 🚀 Lancement du projet

```bash
# Lancer les containers Docker
docker start

# Lancer manuellement le script MQTT
./script.sh
```

Ou automatiser avec crontab pour exécuter toutes les 3 minutes :

```bash
*/3 * * * * /chemin/vers/script.sh
```

---

## 🔒 Accès par défaut

- **phpMyAdmin** : `http://localhost/phpmyadmin`
- **Grafana** : `http://localhost:3000`
- **Login admin** : `admin / passroot`

---

## ✒️ Auteurs

- Mehdi Zerrouki - IUT de Blagnac - BUT1 RT

---

## 🗂️ Licence

Projet académique — pas de licence spécifique. Ne pas reproduire sans autorisation du professeur.
