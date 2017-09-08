// shim native network interfaces on virtual envs (such as wsl & vagrant)
try {
    require( 'os' ).networkInterfaces();
} catch ( e ) {
    require( 'os' ).networkInterfaces = () => ({});
}

let gulp         = require( 'gulp' ),
    sass         = require( 'gulp-sass' ),
    sourcemaps   = require( 'gulp-sourcemaps' ),
    autoprefixer = require( 'autoprefixer' ),
    postCSS      = require( 'gulp-postcss' ),
    watch        = require( 'gulp-watch' ),
    imagemin     = require( 'gulp-imagemin' ),
    browserSync  = require( 'browser-sync' ),
    del          = require( 'del' ),
    cached       = require( 'gulp-cached' ),
    svgmin       = require( 'gulp-svgmin' ),
    svgstore     = require( 'gulp-svgstore' ),
    rename       = require( 'gulp-rename' ),
    cssfont64    = require( 'gulp-cssfont64' ),
    pngquant     = require( "imagemin-pngquant" ),
    typescript   = require( "gulp-typescript" ),
    uglify       = require( 'gulp-uglify' )
;

const sassFilesDir    = '_src/sass',
      cssFiles        = 'styles',
      jsSource        = '_src/ts/standalone',
      jsDestination   = 'js',
      imgSource       = '_src/images',
      imgDestination  = 'images',
      fontsSource     = 'shared/fonts',
      fontDestination = sassFilesDir + '/fonts',
      svgSource       = '_src/svg'
;

let bs;
let tsConfig = typescript.createProject( 'tsconfig.json' );

gulp.task( 'default', [ 'watch' ] ); // default == watch

/* BUILD */
gulp.task( 'build', [ 'sass:build', 'imgmin', 'svgmin', "ts:build" ] );

gulp.task( 'watch', function () {
    bs = browserSync.create( 'syncer' );
    bs.init( {
        open  : false,
        proxy : 'localhost',
        notify: false,
        ui    : {
            port: 8082
        },
    } );
    watch( [
            sassFilesDir + '/**/*.scss',
            '!_src/sass/includes/vendor/bourbon',
            '!_src/sass/includes/vendor/neat' ],
        { usePolling: true },
        function () {
            gulp.start( 'sass:watch' );
        } );
    watch(
        imgSource + '/**/*.*',
        { usePolling: true },
        function () {
            gulp.start( 'imgmin' );
        } );
    watch(
        svgSource + '/*.svg',
        { usePolling: true },
        function () {
            gulp.start( 'svgmin' );
        } );
    watch( [
            'xml_templates/**/*.*',
            'modules/**/*.*',
            jsDestination + '/**/*.js'
        ],
        { usePolling: true },
        () => {
            bs.reload();
        } );
    watch( [
            fontsSource + '/**/*.*'
        ],
        { usePolling: true },
        () => {
            gulp.start( 'fonts:base64' );
        } );
    watch( [
            jsSource + '/**/*.*'
        ],
        { usePolling: true },
        () => {
            gulp.start( "ts:watch" )
        } );
} );

gulp.task( 'ts:watch', function () {
    return gulp.src( jsSource + '/**/*.ts' )
        .pipe( sourcemaps.init() )
        .pipe( tsConfig() )
        .pipe( sourcemaps.write() )
        .pipe( gulp.dest( jsDestination ) );
} );

gulp.task( 'ts:build', function () {
    return gulp.src( jsSource + '/**/*.ts' )
        .pipe( tsConfig() )
        .pipe( uglify( { mangle: true } ) )
        .pipe( gulp.dest( jsDestination ) );
} );

/* SASS */
gulp.task( 'sass:watch', function () {
    return gulp.src( sassFilesDir + '/**/*.scss' )
        .pipe( sourcemaps.init() )
        .pipe( sass().on( 'error', sass.logError ) )
        .pipe(
            postCSS( [ autoprefixer( { browsers: [ 'last 5 versions' ] } ) ] )
        )
        .pipe( sourcemaps.write( 'sourcemaps' ) )
        .pipe( gulp.dest( cssFiles ) )
        .pipe( bs.stream( { match: "**/*.css" } ) );
} );

gulp.task( 'sass:build', function () {
    return gulp.src( sassFilesDir + '/**/*.scss' )
        .pipe( sass( { outputStyle: 'compressed' } ).on( 'error', sass.logError ) )
        .pipe(
            postCSS( [ autoprefixer( { browsers: [ 'last 5 versions' ] } ) ] )
        )
        .pipe( gulp.dest( cssFiles ) );
} );

/* IMAGES */
gulp.task( 'imgmin', function () {
    return gulp.src( imgSource + '/**/*.*' )
        .pipe( cached( 'imgmin' ) )
        .pipe( imagemin( {
            progressive: true,
            verbose    : true
        } ) )
        .on( 'error', function ( err ) {
            console.error( 'Error!', err.message );
        } )
        .pipe( gulp.dest( imgDestination ) )
        .on( 'error', function ( err ) {
            console.error( 'Error!', err.message );
        } );
} );

/* SVG */
gulp.task( 'svgmin', function () {
    return gulp
        .src( svgSource + '/*.svg' )
        .pipe( svgmin() )
        .pipe( rename( { prefix: 'icon-' } ) )
        .pipe( svgstore( {
            inlineSvg: true,
        } ) )
        .pipe( rename( "sprite.svg" ) )
        .pipe( gulp.dest( imgDestination ) );
} );

/* FONTS */
gulp.task( 'fonts:base64', function () {
    del( [
        fontDestination + '/**/*.*'
    ] );
    return gulp.src( fontsSource + '/*.woff' ) // processing multiple formats doesn't work
        .pipe( cssfont64() )
        .pipe( rename( {
            prefix : '_',
            extname: '.scss'
        } ) )
        .pipe( gulp.dest( fontDestination ) );
} );

