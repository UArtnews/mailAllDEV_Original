var gulp = require('gulp'),
    sys = require('sys'),
    exec = require('child_process').exec;

gulp.task('phpunit', function() {
    exec('./vendor/bin/phpunit app/tests', function(error, stdout) {
        sys.puts(stdout);
    });
});

gulp.task('default', function() {
    gulp.watch('**/*.php', ['phpunit']);
});