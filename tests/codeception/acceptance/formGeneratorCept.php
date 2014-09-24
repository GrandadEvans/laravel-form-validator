<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('create a form taking into account all valid parameters');

$base_dir = '../../../';
$package_dir = $base_dir . 'workbench/grandadevans/generate-form';

$command = 'php ' . $base_dir . 'artisan generate:form Foo --dir="' . $package_dir . '/tests/codeception/actualTestResults" --namespace="Bar" --rules="baz:required:email | qux:between(3,6)"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir . '/tests/codeception/expectedTestResults/FooForm.php'));
