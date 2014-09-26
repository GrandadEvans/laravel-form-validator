#!/bin/bash

clear;

echo "

***********
* PHPUnit *
***********

";

phpunit;

echo "

**********
* PHPSec *
**********

";
../../../vendor/bin/phpspec run --format="progress";

echo "

***************
* Codeception *
***************

INFO: If this command hangs the console is asking (in the background) if you want to overwrite the existing file that exists!
Tip: Just press \"y<enter>\" up to 4 times!

";
../../../vendor/bin/codecept run acceptance --silent
