#!/bin/bash

##Get Arguments
unset DB_DUMP TARBALL ACTION

#Does the server has to answer
response=false;

#How much seconds between answer
frequence=10;

#How many clients will talk
clients=1;

while true ; do
    case "$1" in
        -c )
            clients=$2
            shift 2
        ;;
        -f )
            frequence=$2
            shift 2
        ;;
        -r )
            response=true
            shift 2
          break
          ;;
        *)
            break
        ;;
    esac
done;

php ./server.php response clients &
while i < $clients ; do
    php ./client.php i frequence &
    i++;
done
