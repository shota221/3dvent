// if (typeof jQuery.fn.fileupload === 'undefined')    throw new Error('Require jquery-fileupload');
// if (typeof $.ext.ajax === 'undefined')          throw new Error('Require $.ext.ajax');
// if (typeof $.ext.notify === 'undefined')          throw new Error('Require $.ext.notify');
// if (typeof Dropzone === 'undefined')          throw new Error('Require dropzone');
// if (typeof jQuery.fn.dropzone === 'undefined')          throw new Error('Require dropzone');

//if (!window.File || !window.FileReader || !window.FileList && window.Blob) throw new Error('The File APIs are not fully supported in this browser.');
;(function(factory) {
    module.exports = factory( 
        jQuery,
        i18n,
        require('dropzone'),
    );
}(function($, i18n) {
    'use strict';

    window.Dropzone.autoDiscover = false;

    let defaults = {
        notify  : {
            error   : null,
            warn    : null,
            clear   : null,
        },
        csrf       : {
            header  : null,
            token   : null,
            postKey : null,
        },
    }
    
    /**
     * ファイルアップロード
     * 
     * Dropzone wrapper
     * Dropzone >= ie10
     */
    $.fn.fileUpload = function(options) {
        var options = options || {};

        if (typeof options === 'object') {
            return this.each(function() {
                init.call(this, options);
            });
        } else {
            switch (options) {
                case 'getFiles':
                    return getFiles.call(this);

                case 'destroy':
                    destroy.call(this);

                    return this;

                case 'removeFiles':
                    removeFiles.call(this);

                    return this;
            }

            return options.call(this);
        }
    }

    return defaults;
    
    function init(options) {
        var $this = $(this);

        if (! $this.closest('.dropzone').length) console.error('closest dropzone length 0');

        var opts = options || {};

        var paramName = $this.attr('name');

        var errorDetected = false;

        var params = $.extend({
            method              : 'POST',
            parallelMultiple    : true,
            contentType         : false,
            url                 : opts.url,
            headers             : null,
            paramName           : paramName,
            maxFiles            : 1,
            acceptedFiles       : null,
            dictDefaultMessage  : '<span><i class="fa fa-image"></i>&nbsp;Drag and drop your files</span>',
            addRemoveLinks      : true,
            previewsContainer   : null,
            success             : function(file, response) {
                if (opts.success) opts.success(file, response);
            },
            sending             : function(file, xhr, formData) { 
                if (opts.data) $.each(opts.data, function(key, value) {
                    var value = typeof value === 'function' ? value() : value;
                    
                    formData.append(key, value);
                });
            },
            error               : function(file, response) {
                errorDetected = true;

                if (typeof response === 'string') {
                    defaults.notify.warn(response);
                } else {
                    var message = response.result ? (response.result.error ? response.result.error.messages : null) : null;

                    if (message) {
                        defaults.notify.warn(message);
                    } else {
                        defaults.notify.error(i18n('js.message.internal_server_error'));
                    }
                }

                this.removeAllFiles(true);

                if (opts.error) opts.error(response);
            },
            maxfilesexceeded    : function(file) {
                //this.removeFile(file);
            },
            init                : function() {
                var initFiles = options.initFiles || [];

                var ins = this;

                $.each(options.initFiles, function(i, file) {
                    if (file.src && file.src.match('base64') && file.src.match('base64')[0]) {
                        // base64のデコード
                        var bin = atob(file.src.replace(/^.*,/, ''));
                        // バイナリデータ化
                        var buffer = new Uint8Array(bin.length);

                        for (var i = 0; i < bin.length; i++) { buffer[i] = bin.charCodeAt(i); }

                        ins.addFile(new File([buffer.buffer], file.name, { type: file.type }))
                    }
                });
                
            },
            accept: function(file, done) {
                if (errorDetected) {
                    return done('canceled uploading');
                }

                return done();
            },
            queuecomplete: function() {
                errorDetected = false;

                if (opts.complete) opts.complete();
            }
        }, options);

        // CSRF TOKEN取得
        if (defaults.csrf.token) {
            var token = defaults.csrf.token();

            if (defaults.csrf.header) {
                // CSRF-TOKEN HEADER
                params.headers = params.headers || {}; params.headers[defaults.csrf.header] = token;
            }
        }

        params.dictMaxFilesExceeded = i18n('js.message.over_upload_file_count_limit_at_once', {limit: params.maxFiles})

        $this.closest('.dropzone').dropzone(params);

        instance.call($this)
            .on('addedfile', function(file) {
                if (opts.addedFile) opts.addedFile(file);
            })
    }

    function instance() {
        if (!this.closest('.dropzone').length) return {};

        return this.closest('.dropzone').get(0).dropzone;
    }

    function removeFiles() {
        return instance.call(this).removeAllFiles(true);
    }

    function removeFile(file) {
        return instance.call(this).removeFile(file);
    }

    function getFiles() {
        return instance.call(this).getAcceptedFiles();
    }

    function destroy() {
        instance.call(this).destroy();
    }
}));