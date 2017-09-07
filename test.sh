#!/bin/sh

BASE_PATH=`dirname $0`

if [ -e ${BASE_PATH}/vendor/bin/phpunit ]
then
    alias phpunit=${BASE_PATH}/vendor/bin/phpunit
fi

rm -f ${BASE_PATH}/readonly.log
touch ${BASE_PATH}/readonly.log
chmod a-w ${BASE_PATH}/readonly.log

phpunit

rm -f zipkin.log
rm -f ${BASE_PATH}/readonly.log
