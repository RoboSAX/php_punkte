#!/bin/bash

TEMP_DIR="tmp/"
FILE_LIST="
  display/disp_points.php
  display/disp_points_refresh.php
  display/disp_timelist.php
  display/disp_timelist_refresh.php
  display/disp_main.php"


echo ""
echo "### Aktualisierung der RoboSAX-Webseite ###"
echo "  $NOW"



echo ""
echo "1. Lade Einstellungen"
. parse_ini.sh
if [ $? -ne 0 ]; then
    echo "Fehler beim Laden der Einstellungen :-("
    return -1;
    exit -1;
fi

if [ "$Server_base_url" == "" ]; then
    echo "Fehlende Einstellung für [Server].base_url :-(";
    return -1;
    exit -1;
fi
if [ "$Server_liveticker_url" == "" ]; then
    echo "Fehlende Einstellung für [Server].liveticker_url :-(";
    return -1;
    exit -1;
fi



echo ""
echo "2. Lade html-Dateien herunter"
mkdir -p "$TEMP_DIR"

for filename in $FILE_LIST; do
    current_url="${Server_base_url}${filename}"
    wget --directory-prefix="$TEMP_DIR" "$current_url"
    if [ $? -ne 0 ]; then
        echo "Kann Datei \"$filename\" nicht herunterladen :-(";
        return -1;
        exit -1
    fi
done



echo ""
echo "3. Update website"
scp "$TEMP_DIR"*.php "$Server_liveticker_url"
if [ $? -ne 0 ]; then
    echo "Fehler beim Kopieren der Dateien :-(";
    return -1;
    exit -1
fi



echo ""
echo "Fertig :-)"
