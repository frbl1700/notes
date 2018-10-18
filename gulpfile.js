/*
 *  DT173G - Projekt
 *  Fredrik Blank
 */

const gulp = require('gulp');
const sass = require('gulp-sass');
const ts = require('gulp-typescript');
const tsProject = ts.createProject('tsconfig.json');


/*
 *	Kompilera SASS till CSS
 */
gulp.task('sass', function() {
    var task = gulp.src('sass/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('css'));

    return task;
});

/*
 *	Kompilera typescript
 */
gulp.task('compile_ts', function () {
    var task = tsProject
        .src()
        .pipe(tsProject())
        .js
        .pipe(gulp.dest(''));

    return task;
});

/*
 *  Övervaka filändringar
 */
gulp.task('watcher', function() {
    gulp.watch('ts/*.ts', ['compile_ts']);
    gulp.watch('sass/*.scss', ['sass']);
});


/*
 *  Kör tasks som default.
 */
gulp.task('default', ['compile_ts', 'sass', 'watcher']);