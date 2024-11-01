var gulp 			= require('gulp');
var sass 			= require('gulp-sass');
var sourcemaps 		= require('gulp-sourcemaps');
var autoprefixer 	= require('autoprefixer');
var postcss      	= require('gulp-postcss');
var livereload      = require('gulp-livereload');

var paths = {
	scss: {
		theme: 'assets/scss/wpotp-admin.scss',
		watch: ['**/*.scss', '**/*.php', '*.php'],
		dest: 'assets/css/'
	}
};

gulp.task('styles', function ()
{
	return gulp.src(paths.scss.theme)
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(postcss([ autoprefixer({ browsers: ['last 3 versions'] }) ]))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scss.dest))
        .pipe(livereload());
});

gulp.task('watch', function()
{
	livereload.listen();
	gulp.watch(paths.scss.watch, ['styles']);
});

gulp.task('default', ['watch', 'styles']);