#!/bin/bash

SETTINGS_FILE="../config/settings.ini"

if [ ! -e "$SETTINGS_FILE" ]; then
    echo "Einstellungen ($SETTINGS_FILE) nicht gefunden :-(";
    return -1;
    exit   -1;
fi

# load-ini-function from https://serverfault.com/questions/345665
    section=""
    while IFS='= ' read var val; do

        if [[ $var == \[*] ]]; then
            section="$(echo -n "${var:1:-1}" | \
              sed 's/[^a-zA-Z0-9_]//g')";
        elif [[ $val ]]; then
            var="$(echo -n "$var" | sed 's/[^a-zA-Z0-9_]//g')"
            val="$(echo -n "$val" | sed 's/[" ]//g')"
            declare "${section}_${var}=$val"
        fi
    done < "$SETTINGS_FILE"
