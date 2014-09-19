#!/bin/bash

clear;

echo "

PHPSec

";
../../../vendor/bin/phpspec run --format="progress";

echo "

Codeception";
../../../vendor/bin/codecept run acceptance -q
