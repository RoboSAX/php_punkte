# php_punkte
Automatische Punktauswertung des RoboSAX per php und mysql (MariaDB)

[![Build Status](https://travis-ci.org/RoboSAX/php_punkte.svg?branch=master)](https://travis-ci.org/RoboSAX/php_punkte)



## Dokumentation
Alle Änderungen an diesem Repository werden zu einer Generierung der [aktuellen Dokumentation](https://robosax.github.io/doc_punkte/) führen.

## Zur Benutzung: ##
1. config/settings_template.ini in config/settings.ini umbenennen
    - settings_template.ini dient nur als Platzhalter
    - Optionen und Parameter aktualisieren

2. In der Datenbank die Tabelle "robosax" erstellen

3. Datenbank initialisieren
    php/guru/guru_reset_database.php
