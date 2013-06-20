#!/bin/bash
NO_ARGS=0
if [ $# -eq "$NO_ARGS" ] # should check for no arguments
then
	echo "Usage: `basename $0` <ACCOUNTNAME>"
	echo "You must specify the account name"
	exit $E_OPTERROR
fi

echo Synchronize public_html

echo copy source to maruta server...
rsync -avz --exclude '.git' --exclude 'cache' --delete-after ../ 192.168.123.1::$1/
echo Sync Complete!
