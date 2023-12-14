#!/bin/bash

echo "Awaiting services..."

until [ "$(docker inspect -f {{.State.Running}} laravel)" == "true" ]; do
    sleep 0.1;
done;

until [ "$(docker inspect -f {{.State.Health.Status}} postgres)" == "healthy" ]; do
    sleep 0.1;
done;

echo "Services ready"
