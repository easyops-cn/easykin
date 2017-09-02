#!/bin/sh

BASE_PATH=`dirname $0`

if [ -e ${BASE_PATH}/vendor/bin/phpunit ]
then
    alias phpunit=${BASE_PATH}/vendor/bin/phpunit
fi

phpunit
