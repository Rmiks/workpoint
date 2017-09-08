let config             = require( './webpack.config' );
let webpack            = require( 'webpack' );
let CleanWebpackPlugin = require( 'clean-webpack-plugin' );

config.output.chunkFilename = "[name].[chunkhash].js";
config.plugins.push(
    new webpack.optimize.UglifyJsPlugin( {
        minimize: true,
        compress: {
            warnings    : false,
            drop_console: true
        }
    } )
);

config.plugins.push(
    new CleanWebpackPlugin( [ "js", "images", "styles" ], {
        exclude: [ ".gitignore" ]
    } )
);

module.exports = config;