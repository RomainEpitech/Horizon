#!/bin/bash

ENV_FILE=".env"
TEMP_FILE=".env.tmp"

if [ ! -f "$ENV_FILE" ]; then
    echo "File $ENV_FILE not found"
    exit 1
fi

while IFS= read -r line || [[ -n "$line" ]]; do
    if [[ "$line" =~ ^[[:alnum:]_]+=.+$ ]]; then
        var_name=$(echo "$line" | cut -d '=' -f 1)
        case "$var_name" in
            TIMEZONE)
                echo "TIMEZONE=Europe/Paris" >> "$TEMP_FILE"
                ;;
            DB_ACCESS)
                echo "DB_ACCESS=false" >> "$TEMP_FILE"
                ;;
            *)
                echo "$var_name=default" >> "$TEMP_FILE"
                ;;
        esac
    else
        echo "$line" >> "$TEMP_FILE"
    fi
done < "$ENV_FILE"

mv "$TEMP_FILE" "$ENV_FILE"
echo ".env has been reset with the default values."
