var gulp = require('gulp'),
	plugins = require('gulp-load-plugins')(),
	del = require('del'),
	fs = require('fs'),
	cp = require('child_process');

var u = plugins.util,
	c = plugins.util.colors,
	log = plugins.util.log

function logError (err, res) {
	log(c.red('Sass failed to compile'))
	log(c.red('> ') + err.file.split('/')[err.file.split('/').length - 1] + ' ' + c.underline('line ' + err.line) + ': ' + err.message)
}

jsFiles = [
	'./assets/js/main/wrapper_start.js',
	'./assets/js/main/shared_vars.js',
	'./assets/js/modules/*.js',
	'./assets/js/main/main.js',
	'./assets/js/vendor/*.js',
	'./assets/js/main/functions.js',
	'./assets/js/main/wrapper_end.js'
];

var theme_name = 'hive-lite',
	theme = theme_name,
	main_branch = 'master',
	options = {
		silent: true,
		continueOnError: true // default: false
	};

function stylesMain() {
	return gulp.src('assets/scss/**/*.scss')
		.pipe(plugins.sourcemaps.init())
		.pipe(plugins.sass({'sourcemap=auto': true, style: 'expanded'}).on('error', logError))
		.pipe(plugins.autoprefixer())
		.pipe(plugins.sourcemaps.write('.'))
		.pipe(plugins.replace(/^@charset \"UTF-8\";\n/gm, ''))
		.pipe(gulp.dest('./', {mode: "0644"}))
}
stylesMain.description = 'Compiles main css files (ie. style.css editor-style.css)';
gulp.task('styles-main', stylesMain);

function stylesRTL() {
	return gulp.src('style.css')
		.pipe(plugins.rtlcss())
		.pipe(plugins.rename('style-rtl.css'))
		.pipe(gulp.dest('.', {mode: "0644"}))
}
stylesRTL.description = 'Generate style-rtl.css file based on style.css';
gulp.task('styles-rtl', stylesRTL)

function stylesAdmin() {

	return gulp.src('inc/admin/scss/**/*.scss')
		.pipe(plugins.sourcemaps.init())
		.pipe(plugins.sass().on('error', logError))
		.pipe(plugins.autoprefixer())
		.pipe(plugins.replace(/^@charset \"UTF-8\";\n/gm, ''))
		.pipe(gulp.dest('./inc/admin/css'))
}
stylesAdmin.description = 'Compiles WordPress admin Sass and uses autoprefixer';
gulp.task('styles-admin', stylesAdmin )

function stylesPixassistNotice() {

	return gulp.src('inc/admin/pixelgrade-assistant-notice/*.scss')
		.pipe(plugins.sourcemaps.init())
		.pipe(plugins.sass().on('error', logError))
		.pipe(plugins.autoprefixer())
		.pipe(plugins.replace(/^@charset \"UTF-8\";\n/gm, ''))
		.pipe(gulp.dest('./inc/admin/pixelgrade-assistant-notice'))
}
stylesAdmin.description = 'Compiles Pixelgrade Assistant admin notice Sass and uses autoprefixer';
gulp.task('styles-pixassist-notice', stylesPixassistNotice )

function stylesWatch() {
	plugins.livereload.listen();
	return gulp.watch('assets/scss/**/*.scss', stylesMain);
}
gulp.task('styles-watch', stylesWatch);

function stylesSequence(cb) {
	return gulp.series( 'styles-main', 'styles-rtl', 'styles-pixassist-notice', 'styles-admin' )(cb);
}
stylesSequence.description = 'Compile the styles and generate RTL version.';
gulp.task( 'styles', stylesSequence  );


/*
 * javascript stuff
 */

function scripts() {
	return gulp.src(jsFiles)
		.pipe(plugins.concat('main.js'))
		.pipe(plugins.beautify({indentSize: 2}))
		.pipe(gulp.dest('./assets/js/', {"mode": "0644"}));
}
gulp.task('scripts', scripts);

function scriptsWatch() {
	plugins.livereload.listen();
	return gulp.watch('assets/js/**/*.js', scripts);
}
gulp.task('scripts-watch', scriptsWatch);

function watch() {
	gulp.watch('assets/scss/**/*.scss', stylesMain);
	gulp.watch('assets/js/**/*.js', scripts);
}
gulp.task('watch', watch);


/**
 * Copy theme folder outside in a build folder, recreate styles before that
 */
function copyFolder() {
	var dir = process.cwd();
	return gulp.src( './*' )
		.pipe( plugins.exec( 'rm -Rf ./../build; mkdir -p ./../build/' + theme + ';', {
			silent: true,
			continueOnError: true // default: false
		} ) )
		.pipe( plugins.rsync({
			root: dir,
			destination: '../build/' + theme + '/',
			// archive: true,
			progress: false,
			silent: false,
			compress: false,
			recursive: true,
			emptyDirectories: true,
			clean: true,
			exclude: ['node_modules']
		}));
}
gulp.task( 'copy-folder', copyFolder );

/**
 * Clean the folder of unneeded files and folders
 */
function removeUnneededFiles(done) {

	// files that should not be present in build
	files_to_remove = [
		'**/codekit-config.json',
		'node_modules',
		'config.rb',
		'gulpfile.js',
		'package.json',
		'package-lock.json',
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
		'*.sublime-workspace',
		'.DS_Store',
		'**/.DS_Store',
		'__MACOSX',
		'**/__MACOSX',
		'tests',
		'circle.yml',
		'circle_scripts',
		'README.md',
		'.labels',
		'.circleci',
		'.csscomb',
		'.csscomb.json',
		'.codeclimate.yml',
		'tests',
		'.jscsrc',
		'.jshintignore',
		'browserslist',
		'babel.config.js',
		'inc/admin/scss'
	];

	files_to_remove.forEach(function (e, k) {
		files_to_remove[k] = '../build/' + theme + '/' + e;
	});

	return del(files_to_remove, {force: true});
}
gulp.task( 'remove-files', removeUnneededFiles );

function maybeFixBuildDirPermissions(done) {

	cp.execSync('find ./../build -type d -exec chmod 755 {} \\;');

	return done();
}
maybeFixBuildDirPermissions.description = 'Make sure that all directories in the build directory have 755 permissions.';
gulp.task( 'fix-build-dir-permissions', maybeFixBuildDirPermissions );

function maybeFixBuildFilePermissions(done) {

	cp.execSync('find ./../build -type f -exec chmod 644 {} \\;');

	return done();
}
maybeFixBuildFilePermissions.description = 'Make sure that all files in the build directory have 644 permissions.';
gulp.task( 'fix-build-file-permissions', maybeFixBuildFilePermissions );

function maybeFixIncorrectLineEndings(done) {

	cp.execSync('find ./../build -type f -print0 | xargs -0 -n 1 -P 4 dos2unix');

	return done();
}
maybeFixIncorrectLineEndings.description = 'Make sure that all line endings in the files in the build directory are UNIX line endings.';
gulp.task( 'fix-line-endings', maybeFixIncorrectLineEndings );

// -----------------------------------------------------------------------------
// Replace the themes' text domain with the actual text domain (think variations)
// -----------------------------------------------------------------------------
function replaceThemeTextdomainPlaceholder() {

	return gulp.src( '../build/' + theme + '/**/*.php' )
		.pipe( plugins.replace( /['|"]__theme_txtd['|"]/g, '\'' + theme + '\'' ) )
		.pipe( gulp.dest( '../build/' + theme ) );
}
gulp.task( 'txtdomain-replace', replaceThemeTextdomainPlaceholder);

/**
 * Create a zip archive out of the cleaned folder and delete the folder
 */
function createZipFile(){

	var versionString = '';
	//get theme version from styles.css
	var contents = fs.readFileSync("./style.css", "utf8");

	// split it by lines
	var lines = contents.split(/[\r\n]/);

	function checkIfVersionLine(value, index, ar) {
		var myRegEx = /^[Vv]ersion:/;
		if ( myRegEx.test(value) ) {
			return true;
		}
		return false;
	}

	// apply the filter
	var versionLine = lines.filter(checkIfVersionLine);

	versionString = versionLine[0].replace(/^[Vv]ersion:/, '' ).trim();
	versionString = '-' + versionString.replace(/\./g,'-');

	// Right now we create a zip without the version information in the name.
	return gulp.src('./')
		.pipe(plugins.exec('cd ./../; rm -rf ' + theme + '*.zip; cd ./build/; zip -r -X ./../' + theme + '.zip ./; cd ./../; rm -rf build'));
	// return gulp.src('./')
	// 	.pipe(exec('cd ./../; rm -rf' + theme[0].toUpperCase() + theme.slice(1) + '*.zip; cd ./build/; zip -r -X ./../' + theme[0].toUpperCase() + theme.slice(1) + versionString + '.zip ./; cd ./../; rm -rf build'));

}
gulp.task( 'make-zip', createZipFile );

function buildSequence(cb) {
	return gulp.series( 'copy-folder', 'remove-files', 'fix-build-dir-permissions', 'fix-build-file-permissions', 'fix-line-endings', 'txtdomain-replace' )(cb);
}
buildSequence.description = 'Sets up the build folder';
gulp.task( 'build', buildSequence );

function zipSequence(cb) {
	return gulp.series( 'build', 'make-zip' )(cb);
}
zipSequence.description = 'Creates the zip file';
gulp.task( 'zip', zipSequence  );

function updateDemoInstall() {

	var run_exec = cp.exec;

	gulp.src('./')
		.pipe(plugins.prompt.confirm( "This task will stash all your local changes without commiting them,\n Make sure you did all your commits and pushes to the main " + main_branch + " branch! \n Are you sure you want to continue?!? "))
		.pipe(plugins.prompt.prompt({
			type: 'list',
			name: 'demo_update',
			message: 'Which demo would you like to update?',
			choices: ['cancel', 'test.demos.pixelgrade.com/' + theme_name, 'demos.pixelgrade.com/' + theme_name]
		}, function(res){

			if ( res.demo_update === 'cancel' ) {
				console.log( 'No hard feelings!' );
				return false;
			}

			console.log('This task may ask for a github user / password or a ssh passphrase');

			if ( res.demo_update ===  'test.demos.pixelgrade.com/' + theme_name ) {
				run_exec('git fetch; git checkout test; git pull origin ' + main_branch + '; git push origin test; git checkout ' + main_branch + ';', function (err, stdout, stderr) {
					// console.log(stdout);
					// console.log(stderr);
				});
				console.log( " ==== The master branch is up-to-date now. But is the CircleCi job to update the remote test.demo.pixelgrade.com" );
				return true;
			}


			if ( res.demo_update === 'demos.pixelgrade.com/' + theme_name ) {
				run_exec('git fetch; git checkout master; git pull origin test; git push origin master; git checkout ' + main_branch + ';', function (err, stdout, stderr) {
					// console.log(stdout);
					// console.log(stderr);
				});

				console.log( " ==== The master branch is up-to-date now. But is the CircleCi job to update the remote demo.pixelgrade.com" );
				return true;
			}
		}));
}
gulp.task('update-demo', updateDemoInstall);


/**
 * Short commands help
 */
function help(done) {

	var $help = '\nCommands available : \n \n' +
		'=== General Commands === \n' +
		'start              (default)Compiles all styles and scripts and makes the theme ready to start \n' +
		'zip               	Generate the zip archive \n' +
		'build				Generate the build directory with the cleaned theme \n' +
		'help               Print all commands \n' +
		'=== Style === \n' +
		'styles-main        Compiles styles in production mode\n' +
		'styles-rtl         Compiles RTL styles in production mode\n' +
		'=== Scripts === \n' +
		'scripts            Concatenate all js scripts \n' +
		'=== Watchers === \n' +
		'watch              Watches all js and scss files \n' +
		'styles-watch       Watch only styles\n' +
		'scripts-watch      Watch scripts only \n';

	console.log($help);

	done();
}
gulp.task('help', help);
