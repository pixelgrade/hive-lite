var gulp = require('gulp'),
	sass = require('gulp-ruby-sass'),
	prefix = require('gulp-autoprefixer'),
	exec = require('gulp-exec'),
	replace = require('gulp-replace'),
	clean = require('gulp-clean'),
	minify = require('gulp-minify-css'),
	livereload = require('gulp-livereload'),
	concat = require('gulp-concat'),
	notify = require('gulp-notify'),
	beautify = require('gulp-beautify'),
	csscomb = require('gulp-csscomb'),
	mmq = require('gulp-merge-media-queries' ),

jsFiles = [
	'./assets/js/main/wrapper_start.js',
	'./assets/js/main/shared_vars.js',
	'./assets/js/modules/*.js',
	'./assets/js/main/main.js',
	'./assets/js/vendor/*.js',
	'./assets/js/main/functions.js',
	'./assets/js/main/wrapper_end.js'
];


var options = {
	silent: true,
	continueOnError: true // default: false
};

// styles related
gulp.task('styles-dev', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass({'sourcemap=auto': true, style: 'compact'}))
		.on('error', function (e) {
			console.log(e.message);
		})
		.pipe(prefix("last 1 version", "> 1%", "ie 8", "ie 7"))
		.pipe(gulp.dest('./', {"mode": "0644"}))
		.pipe(livereload())
		.pipe(notify('Styles task complete'));
});

gulp.task('styles', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass({'sourcemap=auto': true, style: 'nested'}))
		.on('error', function (e) {
			console.log(e.message);
		})
		.pipe(prefix("last 1 version", "> 1%"))
		.pipe(gulp.dest('./', {"mode": "0644"}))
		.pipe(notify('Styles task complete'));
});

gulp.task('styles-prod', function () {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(sass({sourcemap: false, style: 'expanded'}))
		.on('error', function (e) {
			console.log(e.message);
		})
		.pipe(prefix("last 1 version", "> 1%"))
		.pipe(mmq())
		.pipe(csscomb())
		.pipe(gulp.dest('./', {"mode": "0644"}));
});

gulp.task('styles-watch', function () {
	return gulp.watch('assets/scss/**/*.scss', ['styles-prod']);
});


// javascript stuff
gulp.task('scripts', function () {
	return gulp.src(jsFiles)
		.pipe(concat('main.js'))
		.pipe(beautify({indentSize: 2}))
		.pipe(gulp.dest('./assets/js/', {"mode": "0644"}));
});

gulp.task('scripts-watch', function () {
	return gulp.watch('assets/js/**/*.js', ['scripts']);
});

gulp.task('watch', function () {
	gulp.watch('assets/scss/**/*.scss', ['styles-dev']);
	gulp.watch('assets/js/**/*.js', ['scripts']);
});

// usually there is a default task  for lazy people who just wanna type gulp
gulp.task('start', ['styles', 'scripts'], function () {
	// silence
});

gulp.task('server', ['styles-prod', 'scripts'], function () {
	console.log('The styles and scripts have been compiled for production! Go and clear the caches!');
});


/**
 * Copy theme folder outside in a build folder, recreate styles before that
 */
gulp.task('copy-folder', ['styles-prod', 'scripts'], function () {

	return gulp.src('./')
		.pipe(exec('rm -Rf ./../build; mkdir -p ./../build/hive-lite; rsync -av --exclude="node_modules" ./* ./../build/hive-lite/', options));
});

/**
 * Clean the folder of unneeded files and folders
 */
gulp.task('build', ['copy-folder'], function () {

	// files that should not be present in build
	files_to_remove = [
		'**/codekit-config.json',
		'node_modules',
		'config.rb',
		'gulpfile.js',
		'package.json',
		'pxg.json',
		'build',
		'css',
		'.idea',
		'**/.svn*',
		'**/*.css.map',
		'**/.sass*',
		'.sass*',
		'**/.git*',
		'*.sublime-project',
		'.DS_Store',
		'**/.DS_Store',
		'__MACOSX',
		'**/__MACOSX',
		'README.md'
	];

	files_to_remove.forEach(function (e, k) {
		files_to_remove[k] = '../build/hive-lite/' + e;
	});

	return gulp.src(files_to_remove, {read: false})
		.pipe(clean({force: true}));
});

/**
 * Create a zip archive out of the cleaned folder and delete the folder
 */
gulp.task('zip', ['build'], function(){

	return gulp.src('./')
		.pipe(exec('cd ./../; rm -rf hive-lite.zip; cd ./build/; zip -r -X ./../hive-lite.zip ./hive-lite; cd ./../; rm -rf build'));

});

// usually there is a default task  for lazy people who just wanna type gulp
gulp.task('default', ['start'], function () {
	// silence
});

/**
 * Short commands help
 */

gulp.task('help', function () {

	var $help = '\nCommands available : \n \n' +
		'=== General Commands === \n' +
		'start              (default)Compiles all styles and scripts and makes the theme ready to start \n' +
		'zip               	Generate the zip archive \n' +
		'build				Generate the build directory with the cleaned theme \n' +
		'help               Print all commands \n' +
		'=== Style === \n' +
		'styles             Compiles styles \n' +
		'styles-prod        Compiles styles in production mode \n' +
		'styles-dev         Compiles styles in development mode \n' +
		'=== Scripts === \n' +
		'scripts            Concatenate all js scripts \n' +
		'scripts-dev        Concatenate all js scripts and live-reload \n' +
		'=== Watchers === \n' +
		'watch              Watches all js and scss files \n' +
		'styles-watch       Watch only styles\n' +
		'scripts-watch      Watch scripts only \n';

	console.log($help);

});