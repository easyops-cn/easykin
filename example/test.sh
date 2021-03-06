#!/bin/bash

BASE_PATH=`dirname $0`
echo ${BASE_PATH}

php -S 127.0.0.1:8000 -d always_populate_raw_post_data=-1 -t ${BASE_PATH}/console > /dev/null 2>&1 &
console_pid=$!
php -S 127.0.0.1:8001 -d always_populate_raw_post_data=-1 -t ${BASE_PATH}/login_service > /dev/null 2>&1 &
service_a_pid=$!
php -S 127.0.0.1:8002 -d always_populate_raw_post_data=-1 -t ${BASE_PATH}/business_service > /dev/null 2>&1 &
service_b_pid=$!
php -S 127.0.0.1:8003 -d always_populate_raw_post_data=-1 -t ${BASE_PATH}/mysql_proxy > /dev/null 2>&1 &
service_c_pid=$!

sleep 2;
curl -XGET 'http://127.0.0.1:8000/index.php?abc=123&def'

kill ${console_pid}
kill ${service_a_pid}
kill ${service_b_pid}
kill ${service_c_pid}
