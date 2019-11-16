import {src, dest, task, watch, series, parallel} from 'gulp';
import sass from 'gulp-sass';
import autoprefixer from 'gulp-autoprefixer';
import minify from 'gulp-minify';

function scss_admin(done) {
    src(['./src/scss/*.scss'])
        .pipe(sass({
            errLogToConsole: true,
            outputStyle: 'compressed'
        }))
        .on('error', console.error.bind(console))
        .pipe(autoprefixer({browsers: ['last 2 versions', '> 5%', 'Firefox ESR']}))
        .pipe(dest('./dist/css'));
    done();
}

function js_admin(done) {
    src(['./src/script/*.js'])
        .pipe(minify())
        .pipe(dest('./dist/script'));
    done();
}

function watch_files() {
    watch('./src/scss/**/*.scss', series(scss_admin));
    watch('./src/script/**/*.js', series(js_admin));
}

task("default", parallel(watch_files));