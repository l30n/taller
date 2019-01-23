const {
    mix
} = require('laravel-mix');

const fs = require('fs');
const path = require('path');

mix.webpackConfig({
    module: {
        rules: [{
            test: /\.css$/,
            loader: "style-loader!css-loader"
        }, ]
    }
});

function deleteFiles(directory) {
    fs.readdir(directory, (err, files) => {
        if (err) throw err;

        for (const file of files) {
            fs.unlink(path.join(directory, file), err => {
                if (err) throw err;
            });
        }
    });
}

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copyDirectory('resources/img', 'public/img');

deleteFiles('public/js');
deleteFiles('public/css');

mix.js('resources/js/app.js', 'public/js').extract(['vue']).version();
mix.sass('resources/sass/app.scss', 'public/css').version();

mix.disableNotifications();
