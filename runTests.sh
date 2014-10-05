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
phpspec run --format="progress";

echo "

***************
* Codeception *
***************
INFO: This is an acceptance test and as such may take a minute or two to complete.
      Especially if run over an SSH connection for example. Please...be patient
";
codecept run acceptance --silent
