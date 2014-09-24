var gulp = require('gulp');
//var notify = require('gulp-notify');
//var codecept = require('gulp-codeception');
//var phpspec = require('gulp-phpspec');
var phpunit = require('gulp-phpunit');

gulp.task('default', function() {

});

gulp.task('phpunit', function() {
	gulp.src('./tests/phpunit/Grandadevans/GenerateForm/Command/FormGenerateCommandTest.php')
		.pipe(phpunit('./vendor/bin/phpunit', {
				"clear": true,
				"configurationFile": "./phpunit.xml"

			}))
});
