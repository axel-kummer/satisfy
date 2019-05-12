#!/bin/bash

## Ensure all driectories have the correct permissions
chown www-data -R /var/www/satisfy

## Import mounted root certificates
if [ -d "/var/ca-certificates" ]; then
    for CA_FILE in $(ls -C "/var/ca-certificates"); do
        SOURCE_FILE="/var/ca-certificates/${CA_FILE}"
        TARGET_FILE="/usr/local/share/ca-certificates/${CA_FILE}"

        firstLine="$(head -n 1 ${SOURCE_FILE})"
        lastChar="$(tail -c 1 ${SOURCE_FILE})"

        if [ "${firstLine}" != "-----BEGIN CERTIFICATE-----" ]; then
            echo "${CA_FILE} is not a valid certificate"
            break;
        fi
        if [ "${lastChar}" == "-" ]; then
            cat ${SOURCE_FILE} > ${TARGET_FILE}
            echo "" >> ${TARGET_FILE}
        else
            cat ${SOURCE_FILE} > ${TARGET_FILE}
        fi
    done

    update-ca-certificates
fi

"$@"