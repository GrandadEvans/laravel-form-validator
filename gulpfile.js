var gulp = require('gulp');
var notify = require('gulp-notify');
var phpspec = require('gulp-phpspec');
var phpunit = require('gulp-phpunit');


gulp.task('phpunit', function() {
	var options = {
		notify: true,
		clear: true,
		noInteraction: true,
		formatter: "pretty",
		"configurationFile": "./phpunit.xml"
	};
	gulp.src([
		'src/Grandadevans/GenerateForm/Command/FormGeneratorCommand.php',
		'tests/phpunit/Grandadevans/GenerateForm/Command/FormGenerateCommandTest.php'
		])
		.pipe(phpunit('phpunit', options))
		.on('error', notify.onError({
			title: "Testing Failed",
			message: "Error(s) occurred during test..."
		}))
		.pipe(notify({
			title: "Testing Passed",
			message: "All tests have passed..."
		})
	);
});

gulp.task('phpspec', function() {
	var options = {
		notify: true,
		clear: true,
		noInteraction: true,
		formatter: "pretty"
	};
	gulp.src(['tests/spec/**/*Spec.php','src/**/*'])
		.pipe(phpspec('./vendor/bin/phpspec run', options))
		.on('error', notify.onError({
			title: "Testing Failed",
			message: "Error(s) occurred during test..."
		}))
		.pipe(notify({
			title: "Testing Passed",
			message: "All tests have passed..."
		}));
});

gulp.task('watch-phpunit', function () {
	gulp.watch([
		'src/Grandadevans/GenerateForm/Command/FormGeneratorCommand.php',
		'tests/phpunit/Grandadevans/GenerateForm/Command/FormGenerateCommandTest.php'
	], ['phpunit']);
});

gulp.task('watch-phpspec', function () {
	gulp.watch([
		'src/**/*',
		'tests/spec/**/*'
	], ['phpspec']);
});

gulp.task('notify-watching', function() {
	gulp.src('./')
		.pipe(notify({
			title: 'Automatic testing ACTIVE',
			message: 'Automatic testing for PHPUnit/PHPSpec files is ACTIVE.'
		}));
});


gulp.task('default', ['notify-watching', 'watch-phpspec', 'watch-phpspec']);

