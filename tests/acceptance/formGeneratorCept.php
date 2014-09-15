<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('create a form taking into account all valid parameters');

$command = 'php artisan generate:form Foo --dir="tests/actualTestResults" --namespace="Bar" --rules="baz:required | qux:between(3,6)"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form Generated!');

$I->openFile('tests/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents('tests/expectedTestResults/FooForm.php'));
