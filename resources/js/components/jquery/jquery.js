/**
 * module jquery
 * 
 * select2など内部でdefine(['jquery'], ..)するモジュールを利用する際、
 * global設定したjQueryを利用するようにnode_modulesのjqueryをラップ
 * 
 */
define(function() { 
    // globalがあればそれを利用
    if (window.jQuery) return window.jQuery;

    // node_modulesから初期化
    return require('node_modules/jquery/src/jquery');
});
