try {
    require( 'os' ).networkInterfaces();
} catch ( e ) {
    require( 'os' ).networkInterfaces = () => ({});
}

let path                = require( 'path' );
let webpack             = require( 'webpack' );
const { CheckerPlugin } = require( 'awesome-typescript-loader' );


module.exports = {
    entry       : {
        index: './_src/ts/app/index.ts'
    },
    output      : {
        filename     : '[name].js',
        path         : path.resolve( __dirname, 'js' ),
        publicPath   : "/js/",
        chunkFilename: "[name].js"
    },
    resolve     : {
        extensions: [ '.ts', '.js' ],
        modules   : [
            path.resolve( __dirname, '_src/js' ),
            'node_modules'
        ]
    },
    module      : {
        rules: [
            {
                test   : /\.ts?$/,
                exclude: [ /node_modules/ ],
                loader : 'awesome-typescript-loader'
            },
            {
                test: /\.css$/,
                use : [ 'style-loader', 'css-loader' ]
            }
        ]
    },
    plugins     : [
        new CheckerPlugin()
    ],
    watchOptions: {
        aggregateTimeout: 100,
        poll            : 1000,
        ignored         : '/node_modules'
    },
    cache       : false
};