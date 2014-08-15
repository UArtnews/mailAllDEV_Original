var gulp = require('gulp'),
    sys = require('sys'),
    exec = require('child_process').exec,
    jshint = require('jshint'),
    less = require('gulp-less'),
    path = require('path');

gulp.task('phpunit', function() {
    exec('./vendor/bin/phpunit app/tests', function(error, stdout) {
        sys.puts(stdout);
    });
});

gulp.task('less', function() {
    return gulp.src(['./less/bootstrap/bootstrap.less'])
        .pipe(less())
        .pipe(gulp.dest('./public/css'));
});

gulp.task('default', function() {
    gulp.watch('**/*.php', ['phpunit']);
    gulp.watch('**/*.less', ['less']);
});