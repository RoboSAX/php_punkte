#!/bin/bash

NOW_YEAR="$(date +%Y)"
NOW="$(date +%Y_%m_%d_%H%M)"
PATH_BACKUP="backup_rs_${NOW_YEAR}/"



echo ""
echo "### Backup der RoboSAX Datenbank ###"
echo "  $NOW"



echo ""
echo "1. Lade Einstellungen"
. parse_ini.sh
if [ $? -ne 0 ]; then
    echo "Fehler beim Laden der Einstellungen :-("
    return -1;
    exit -1;
fi

if [ "$DB_host" == "" ]; then
    echo "Fehlende Einstellung für [DB].host :-(";
    return -1;
    exit -1;
fi
if [ "$DB_username" == "" ]; then
    echo "Fehlende Einstellung für [DB].username :-(";
    return -1;
    exit -1;
fi
if [ "$DB_password" == "" ]; then
    echo "Fehlende Einstellung für [DB].password :-(";
    return -1;
    exit -1;
fi
if [ "$DB_database" == "" ]; then
    echo "Fehlende Einstellung für [DB].database :-(";
    return -1;
    exit -1;
fi
if [ "$Files_backup_path" == "" ]; then
    echo "Fehlende Einstellung für [Files].backup_path :-(";
    return -1;
    exit -1;
fi



echo ""
echo "2. Erzeuge lokales Backup"
PATH_BACKUP_LOCAL="${Files_backup_path}${PATH_BACKUP}${NOW}/"
mkdir -p "$PATH_BACKUP_LOCAL"
if [ $? -ne 0 ]; then
    echo "Kann Backup-Ordner ("$PATH_BACKUP_LOCAL") nicht anlegen :-("
    return -1;
    exit -1;
fi

mysqldump -u "$DB_username" "--password=$DB_password" \
  "$DB_database" "--host=$DB_host"\
  > "${PATH_BACKUP_LOCAL}${DB_database}.sql"
if [ $? -ne 0 ]; then
    echo "Kann Datenbank nicht auslesen :-("
    return -1;
    exit -1;
fi



echo ""
echo "Fertig :-)"
