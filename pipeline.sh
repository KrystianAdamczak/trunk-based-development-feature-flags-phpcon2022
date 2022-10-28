#!/bin/bash

cd main
composer install
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi

FEATURE_FLAG_SHOW_PRODUCT_DETAILS_ON_LIST=1 composer run tests
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi

composer run tests
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi
cd ..
docker-compose build
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi

docker-compose stop
docker-compose create
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi

docker-compose start
ret=$?
if [ $ret != 0 ]
then
    exit $ret
fi
