#!/bin/bash

function update_or_append() {
    local key=$1
    local value=$2
    local file=".env"

    if grep -q "^$key=" "$file"; then
        if [[ "$(uname)" == "Darwin" ]]; then
            sed -i '' "s/^$key=.*/$key=$value/" "$file"
        else
            sed -i "s/^$key=.*/$key=$value/" "$file"
        fi
    else
        echo "$key=$value" >> "$file"
    fi
}

clear;
echo "Start the database config ? (y/n)"
read dbConfig

if [ "$dbConfig" = "y" ]; then 
    while true; do
        echo "Enter the database name: (default: host)"
        read dbName
        dbName=${dbName:-host}

        echo "Enter the database password: (default: root)"
        read dbPass
        dbPass=${dbPass:-root}

        echo "Enter the database username: (default: root)"
        read dbUser
        dbUser=${dbUser:-root}

        echo "Enter the database charset: (default: utf8)"
        read dbChar
        dbChar=${dbChar:-utf8}

        echo "Enter the database port: (default: 8888)"
        read dbPort
        dbPort=${dbPort:-8888}

        echo "Enter the database host: (default: localhost)"
        read dbHost
        dbHost=${dbHost:-localhost}

        echo "You have entered the following information:"
        echo "Database Name: $dbName"
        echo "Database Password: $dbPass"
        echo "Database Username: $dbUser"
        echo "Database Charset: $dbChar"
        echo "Database Port: $dbPort"
        echo "Database Host: $dbHost"
        echo "Are the information correct? (y/n)"
        read dbConfirm

        if [ "$dbConfirm" = "y" ]; then
            break
        fi
    done

    update_or_append "DB_NAME" "$dbName"
    update_or_append "DB_PASSWORD" "$dbPass"
    update_or_append "DB_USERNAME" "$dbUser"
    update_or_append "DB_CHARSET" "$dbChar"
    update_or_append "DB_PORT" "$dbPort"
    update_or_append "DB_HOST" "$dbHost"

    echo "Database configuration saved to .env file."
    chmod +x settings/scripts/newDb.php
    php settings/scripts/newDb.php "$dbName" "$dbUser" "$dbPass" "$dbHost" "$dbPort" "$dbChar"
fi