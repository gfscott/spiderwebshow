var gulp          = require('gulp'),
    gutil         = require('gulp-util'),
    uglify        = require('gulp-uglify'),
    sass          = require('gulp-sass'),
    postcss       = require('gulp-postcss'),
    autoprefixer  = require('autoprefixer'),
    cssnano       = require('cssnano'),
    flexfixer     = require('postcss-flexboxfixer'),
    mqpacker      = require('css-mqpacker'),
    sourcemaps    = require('gulp-sourcemaps'),
    rename        = require('gulp-rename'),
    rev           = require('gulp-rev'),
    findreplace   = require('gulp-replace'),
    del           = require('del'),
    concat        = require('gulp-concat');

gulp.task('default', ['compile-scripts', 'compile-styles']);

var paths = {
  scripts: './js/src/*.js',
  styles: './css/src/*.scss'
};

// Combo task. Delete all compiled files, re-compile, and apply cachebusting jazz-hands to enable long-lived filenames. Do this before a deploy
gulp.task('version', ['clean', 'default', 'cachebust']);

// for local development, roll back cachebusting; it doesn't play well with gulp.watch, so just revert to the un-revved filenames.
gulp.task('deversion', ['clean', 'default', 'cacherepair']);

gulp.task('clean', function(){

  del([
    './css/*.css',
    './css/*.map',
    './css/rev-manifest.json',
    './js/*.js',
    './js/*.map',
    './js/rev-manifest.json'
  ]);

});

gulp.task('cachebust', function(){

  // busting the CSS cached filename
  var cssManifest = require('./css/rev-manifest.json'); // gulp-rev writes a manifest file holding the file and its rev'ed filename
  var cssOld      = /\/css\/(?:.+)\.css/; // regex search pattern for the css file path we want to replace in the header
  var cssNew      = "/css/" + cssManifest["style.min.css"]; // from the manifest file, read the value of the rev'ed filename, from its key, which is the un-rev'ed filename

  // open the header file, do a find-replace, and rewrite in place
  gulp.src('./header.php')
    .pipe(findreplace( cssOld, cssNew ))
    .pipe(gulp.dest('./'));

  // busting the JS cached filename (same method as for css)
  var jsManifest  = require('./js/rev-manifest.json');
  var jsOld       = /\/js\/(?:.+)\.js/;
  var jsNew       = "/js/" + jsManifest["script.min.js"];

  // open the header file, do a find-replace, and rewrite in place
  gulp.src('./footer.php')
    .pipe(findreplace( jsOld, jsNew ))
    .pipe(gulp.dest('./'));


});

gulp.task('cacherepair', function(){

  var cssOld    = /\/css\/(?:.+)\.css/;
  var cssNew    = "/css/style.css";

  gulp.src('./header.php')
    .pipe(findreplace( cssOld, cssNew ))
    .pipe(gulp.dest('./'));

  var jsOld    = /\/js\/(?:.+)\.js/;
  var jsNew    = "/js/script.js";

  gulp.src('./footer.php')
    .pipe(findreplace( jsOld, jsNew ))
    .pipe(gulp.dest('./'));

});


gulp.task('compile-scripts', function(){

  // Return a concatenated but not uncompressed JS file
  gulp.src(paths.scripts)
    .pipe(sourcemaps.init())
    .pipe(concat('script.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('js'));

  gulp.src(paths.scripts)
    .pipe(concat('script.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('js'))
    .pipe(rev())
    .pipe(gulp.dest('js'))
    .pipe(rev.manifest())
    .pipe(gulp.dest('js'));

});


gulp.task('compile-styles', function(){

  // Return a concatenated, sassed and postcssed, but uncompressed CSS file, with sourcemaps
   gulp.src(paths.styles)
  // concatenate, sass, postcss, write sourcemaps and then output final css file
    .pipe(sourcemaps.init())
    .pipe(concat('style.css'))
    .pipe(sass())
    .pipe(postcss([
      autoprefixer(),
      flexfixer(),
      mqpacker()
    ]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('css'));

  gulp.src(paths.styles)
  // then produce a normal minified CSS file
    .pipe(concat('style.min.css'))
    .pipe(sass())
    .pipe(postcss([
      autoprefixer(),
      mqpacker(),
      flexfixer(),
      cssnano()
    ]))
    .pipe(gulp.dest('css'))

  // then produce a revision-hashed file (it will be identical to style.min.css, but with hash filename to facilitate forever-caching
    .pipe(rev())
    .pipe(gulp.dest('css'))

  // then produce the revision-hash manifest file. This is necessary to allow us to get the hash filename for find-replace in the PHP file.
    .pipe(rev.manifest())
    .pipe(gulp.dest('css'));
});



gulp.task('watch', function() {
  gulp.watch(paths.scripts, ['default']);
  gulp.watch(paths.styles, ['default']);
});
