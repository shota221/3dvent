/********************************
 * jQuery.notify 拡張 
 ********************************/

if (typeof jQuery === 'undefined') throw new Error('required jQuery');

require('jquery-notify-legacy/dist/notify'); // node_modules/{name}/dist内にjsがいる場合：https://stackoverflow.com/questions/34123358/webpack-cannot-find-node-module-bootstrap-multiselect

;(function($) {
    
    $.ext = $.ext || {};

    /**
     * $.fn.notifyラッパオブジェクト
     *
     */
    $.ext.notify = {
        info: function(message) {
            $.notify(message, { className: 'info', style: 'box', adjustScroll: true, displayTime: 5000 });
        },
        success: function(message) {
            $.notify(message, { className: 'success', style: 'box', adjustScroll: true, displayTime: 5000 });
        },
        warn: function(message) {
            $.notify(message, { className: 'warn', style: 'box', sticky: false, adjustScroll: true, displayTime: 4000 });
        },
        error: function(message) {
            $.notify(message, { className: 'error', style: 'box', adjustScroll: true });
        },
        // clear: function() {
        //     $(document).find('.notify.box').remove();
        // }
    }

    $.notify.addStyle('box', {
        html: '<div>\n<span data-notify-text></span>\n</div>',
        classes: {
            base: {
                'border-radius': ' 3px',
                'border': ' 1px solid #46b8da',
                'padding': ' 5px 20px',
                'margin-top': ' 5px',
                'left': '0',
                'right': '0',
                'color': '#FFFFFF',
                'min-height': '20px',
                'font-family': ' "Helvetica Neue", Helvetica, Arial, sans-serif',
                'right':0,
                'width':'400px',
                'left':'auto',
                'padding':'25px',
                'background': '#5bc0de',
                /* IE10 Consumer Preview */ 
                'background-image': ' -ms-linear-gradient(top, #46b8da 0%, #5bc0de 100%)',
                /* Mozilla Firefox */ 
                'background-image': ' -moz-linear-gradient(top, #46b8da 0%, #5bc0de 100%)',
                /* Opera */ 
                'background-image': ' -o-linear-gradient(top, #46b8da 0%, #5bc0de 100%)',
                /* Webkit (Safari/Chrome 10) */ 
                'background-image': ' -webkit-gradient(linear, left top, left bottom, color-stop(0, #46b8da), color-stop(1, #5bc0de))',
                /* Webkit (Chrome 11+) */ 
                'background-image': ' -webkit-linear-gradient(top, #46b8da 0%, #5bc0de 100%)',
                /* W3C Markup, IE10 Release Preview */ 
                'background-image': ' linear-gradient(to bottom, #46b8da 0%, #5bc0de 100%)',
            },
            success: {
                'border': ' 1px solid #4cae4c',
                'background': ' #5cb85c',
                /* IE10 Consumer Preview */ 
                'background-image': '  -ms-linear-gradient(top, #4cae4c 0%, #5cb85c 100%)',
                /* Mozilla Firefox */ 
                'background-image': '  -moz-linear-gradient(top, #5cb85c 0%, #4cae4c 100%)',
                /* Opera */ 
                'background-image': '  -o-linear-gradient(top, #5cb85c 0%, #4cae4c 100%)',
                /* Webkit (Safari/Chrome 10) */ 
                'background-image': '  -webkit-gradient(linear, left top, left bottom, color-stop(0, #4cae4c), color-stop(1, #5cb85c))',
                /* Webkit (Chrome 11+) */ 
                'background-image': '  -webkit-linear-gradient(top, #5cb85c 0%, #4cae4c 100%)',
                /* W3C Markup, IE10 Release Preview */ 
                'background-image': '  linear-gradient(to bottom, #5cb85c 0%, #4cae4c 100%)',
            },
            error:  {
                'border': '  1px solid #d43f3a',
                'background': ' #d9534f',
                /* IE10 Consumer Preview */ 
                'background-image': '  -ms-linear-gradient(top, #d43f3a 0%, #d9534f 100%)',
                /* Mozilla Firefox */ 
                'background-image': '  -moz-linear-gradient(top, #d43f3a 0%, #d9534f 100%)',
                /* Opera */ 
                'background-image': '  -o-linear-gradient(top, #d43f3a 0%, #d9534f 100%)',
                /* Webkit (Safari/Chrome 10) */ 
                'background-image': '  -webkit-gradient(linear, left top, left bottom, color-stop(0, #d43f3a), color-stop(1, #d9534f))',
                /* Webkit (Chrome 11+) */ 
                'background-image': '  -webkit-linear-gradient(top, #d43f3a 0%, #d9534f 100%)',
                /* W3C Markup, IE10 Release Preview */ 
                'background-image': '  linear-gradient(to bottom, #d43f3a 0%, #d9534f 100%)',
            },
            warn:   {
                'border': '  1px solid #eea236',
                'background': ' #f0ad4e',
                /* IE10 Consumer Preview */ 
                'background-image': '  -ms-linear-gradient(top, #eea236 0%, #f0ad4e 100%)',
                /* Mozilla Firefox */ 
                'background-image': '  -moz-linear-gradient(top, #eea236 0%, #f0ad4e 100%)',
                /* Opera */ 
                'background-image': '  -o-linear-gradient(top, #eea236 0%, #f0ad4e 100%)',
                /* Webkit (Safari/Chrome 10) */ 
                'background-image': '  -webkit-gradient(linear, left top, left bottom, color-stop(0, #eea236), color-stop(1, #f0ad4e))',
                /* Webkit (Chrome 11+) */ 
                'background-image': '  -webkit-linear-gradient(top, #eea236 0%, #f0ad4e 100%)',
                /* W3C Markup, IE10 Release Preview */ 
                'background-image': '  linear-gradient(to bottom, #eea236 0%, #f0ad4e 100%)',
            }
        }
    });
})(jQuery);