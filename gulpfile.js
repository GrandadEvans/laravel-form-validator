var gulp = require('gulp');
var notify = require('gulp-notify');
//var codecept = require('gulp-codeception');
var phpspec = require('gulp-phpspec');
var phpunit = require('gulp-phpunit');

gulp.task('phpunit', function() {
	gulp.src('./tests/phpunit/Grandadevans/GenerateForm/Command/FormGenerateCommandTest.php')
		.pipe(phpunit('./vendor/bin/phpunit', {
				"clear": true,
				"configurationFile": "./phpunit.xml"

			}))
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

// set watch task to look for changes in test files
gulp.task('watch-phpspec', function () {
	gulp.watch(['tests/spec/**/*Spec.php','src/**/*'], ['phpspec']);
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', ['phpspec', 'watch-phpspec']);
