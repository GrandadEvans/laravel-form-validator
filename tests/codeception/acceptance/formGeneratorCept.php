<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('create a form taking into account all valid parameters');

$base_dir = '../../../';
$package_dir = $base_dir . 'workbench/grandadevans/generate-form';


/*
 * Test all options
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo --dir="' . $package_dir . '/tests/codeception/actualTestResults" --namespace="Bar" --rules="baz:required:email | qux:between(3,6)"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
                                              '/tests/codeception/expectedTestResults/FooForm-with-all-options.php'));

/*
 * Test no namespace
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo --dir="' . $package_dir . '/tests/codeception/actualTestResults" --rules="baz:required:email | qux:between(3,6)"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
	'/tests/codeception/expectedTestResults/FooForm-with-no-namespace.php'));

/*
 * Test no namespace and no rules
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo --dir="' . $package_dir . '/tests/codeception/actualTestResults"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
      '/tests/codeception/expectedTestResults/FooForm-with-no-namespace-and-no-rules.php'));

/*
 * Test no namespace and no rules or a directory
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
      '/tests/codeception/expectedTestResults/FooForm-with-no-namespace-and-no-rules-or-dir.php'));


unlink($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
unlink('app/Forms/FooForm.php');
