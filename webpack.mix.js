const webpack = require('webpack');

const mix = require('laravel-mix');

const glob = require('glob');

const path = require('path');

//const fs = require('fs');

const appSrcDir = 'resources';

const appDistDir = 'public';

/**
 * webpack config
 * 
 * @type {Object}
 */
mix.webpackConfig({
    resolve: {
        alias: {
            'node_modules': path.join(__dirname, 'node_modules'),
            // jqueryオーバーライド
            'jquery': path.join(__dirname, 'resources/js/components/jquery/jquery'),
        }           
    },
    plugins: [
        // Ignore all locale files of moment.js
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    ],
});



/**
 * admin,webディレクトリに対し、入れ子のディレトリ内のjs,scssファイルをまとめ、そのディレクトリ名でパブリッシュ
 *
 * @param  {[type]} route [admin|web]
 * @param  {[type]} ext   [js|css]
 * @return 
 */
function compileToPublishResources(route, ext) {
    var 
        entries = {} 
        ,mixMethod
        ,src     = {
            dir: path.join(appSrcDir, ext, route),
            ext: ext
        }
        ,dist    = { 
            dir: path.join(ext, route), 
            ext: ext 
        }
    ;

    switch (src.ext) {
        case 'scss':    
            mixMethod   = mix.sass;  
            src.dir     = path.join(appSrcDir, 'sass', route);
            dist.dir    = path.join('css', route);
            dist.ext    = 'css'; 
            break;
        case 'js':      
            mixMethod = mix.js;     
            break;
    }

    glob.sync('**/*.' + src.ext, {
        cwd     : src.dir,
        ignore  : '**/_*.' + src.ext,
    }).map(function (file) {
        var key = path.dirname(file), value = path.join(src.dir, file);

        if ('.' == key) key = path.basename(file, path.extname(file));

        if (! Array.isArray(entries[key])) entries[key] = [];

        entries[key].push(value);
    });

    console.log(('src.ext=' + src.ext + ' route=' + route), entries); // debug

    Object.keys(entries).forEach(function(key) {
        if ('scss' === src.ext) {
            // 配列は受け付けない
            entries[key].forEach(function (file) {
                mixMethod(
                    file, 
                    path.join(
                        dist.dir,
                        (path.basename(file, path.extname(file)) + '.' + dist.ext)
                    )
                ); 
            })
        } else {
            mixMethod(
                entries[key], 
                path.join(
                    dist.dir, 
                    (key + '.' + dist.ext)
                )
            );
        }
    });
}

/**
 * admin,webディレクトリに対し、画像ファイルをpublicにコピー
 * mix.copyDirectoryは使えない（manifestに記載されないため）
 * 
 * @param  {[type]} route [description]
 * @param  {[type]} dir [description]
 * @return {[type]}       [description]
 */
function copyToPublishResources(route, dir) {
    var srcDir  = path.join(appSrcDir , dir, route);
    var distDir = path.join(appDistDir, dir, route);

    glob.sync('**/*', {
        cwd     : srcDir,
        ignore  : '**/_*',
    }).map(function (file) {
        var src = path.join(srcDir, file), dist = path.join(distDir, file);

        console.log('coping...', src, dist);
        
        mix.copy(src, dist);
    });
}

if (mix.inProduction()) {
    console.log('production added version');
    
    mix.version();
}


/*******
 * JS 
 *******/

compileToPublishResources('admin', 'js');
compileToPublishResources('form', 'js');
compileToPublishResources('org', 'js');
compileToPublishResources('manual', 'js');
compileToPublishResources('common/adminlte', 'js');

/*******
 * SCSS
 *******/

compileToPublishResources('admin', 'scss');
compileToPublishResources('form', 'scss');
compileToPublishResources('org', 'scss');
compileToPublishResources('manual', 'scss');
compileToPublishResources('common/adminlte', 'scss');

/*******
 * CSS
 *******/

copyToPublishResources('admin', 'css');
copyToPublishResources('form', 'css');
copyToPublishResources('org', 'css');
copyToPublishResources('manual', 'css');


/*******
 * IMAGE
 *******/

copyToPublishResources('admin', 'images');
copyToPublishResources('form', 'images');
copyToPublishResources('org', 'images');
copyToPublishResources('manual', 'images');

// for debug
//throw new Error();
