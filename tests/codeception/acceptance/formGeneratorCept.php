<?php 

/*
 * If the test results exist from a previous test then get rid of them!
 */
if (false !== file_exists('tests/codeception/actualTestResults/FooForm.php')) {
	unlink('tests/codeception/actualTestResults/FooForm.php');
}

if (false !== file_exists('app/Forms/FooForm.php')) {
	unlink('app/Forms/FooForm.php');
}



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

unlink('tests/codeception/actualTestResults/FooForm.php');



/*
 * Test no namespace and no rules
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo --dir="' . $package_dir . '/tests/codeception/actualTestResults"';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/tests/codeception/actualTestResults/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
      '/tests/codeception/expectedTestResults/FooForm-with-no-namespace-and-no-rules.php'));

unlink('tests/codeception/actualTestResults/FooForm.php');



/*
 * Test no namespace and no rules or a directory
 */
$command = 'php ' . $base_dir . 'artisan generate:form Foo';
$I->runShellCommand($command);

$I->seeInShellOutput('Form has been saved to');

$I->openFile($package_dir . '/app/Forms/FooForm.php');
$I->canSeeFileContentsEqual(file_get_contents($package_dir .
      '/tests/codeception/expectedTestResults/FooForm-with-no-namespace-and-no-rules-or-dir.php'));

unlink('app/Forms/FooForm.php');


