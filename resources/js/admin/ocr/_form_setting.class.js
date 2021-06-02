;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../../components/util/class.js'),
        require('../../components/form.class.js'),
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    const 
        $inputUrlCreateQueue = $('input#url-async-create-queue')

        , $btnResetFileUploader = $('.btn.reset-file-uploader')

        , $settingFileArea = $('#setting-file')
    ;

    let FormSettingClass;

    return FormSettingClass = ClassUtils.Extend(BaseFormClass, function FormSettingClass($elem) {
        FormSettingClass.prototype.__super__.constructor.call(this, $elem);

        this.$selectFormatId = $('select[name="format_id"]')

        this.$fileUploadInput = $('input[name="file"]')
    }, {
        build: function() {
            var self = this, currentAddedFile;

            FormSettingClass.prototype.__super__.build.call(this);

            // TODO 暫定非表示たいおう　
            $('<style />')
                .append('<style />')
                .append('.dz-preview { padding: 15px; }')
                .append('.dz-image img { width:100%; }')
                .append('.dz-size { font-size:20px; }')
                .append('.dz-filename { font-size:20px; }')
                .append('.dz-progress { display:none; }')
                .append('.dz-success-mark { display:none; }')
                .append('.dz-error-mark { display:none; }')
                .append('.dz-remove { display:none; }')
                .appendTo('head')

            $btnResetFileUploader
                .on('click', function() {
                    resetFileUploader.call(self);

                    return false;
                })

            this.$fileUploadInput
                .fileUpload({
                    url             : $inputUrlCreateQueue.val(),
                    parallelUploads : 1,
                    maxFiles        : 1,
                    thumbnailWidth  : 200,
                    //previewsContainer: '#upload-preview',
                    acceptedFiles   : 'image/jpg,image/jpeg,image/gif,image/png',
                    data            : { format_id : function() { return self.$selectFormatId.val(); } },
                    dictDefaultMessage  : '<span class="desc-file-uploader">クリックまたはドラッグドロップ<br />でアップロード</span>',
                    complete: function() {
                    },
                    success: function(file, response) {
                        self.trigger('uploadedFile', file, response);
                    },
                    addedFile: function() {
                        $('.dz-message').hide();
                    }
                });

            this.$selectFormatId
                .on('change', function() {
                    var formatId = $(this).val();

                    resetFileUploader.call(self);

                    if (! formatId) {
                        $settingFileArea.hide();
                    } else {
                        $settingFileArea.show();
                    }

                    self.trigger('changeFormatId', formatId);

                    return false;
                })
                .selectBox('initialValue', null);

            return this;
        }

    });

    function resetFileUploader() {
        this.$fileUploadInput.fileUpload('removeFiles');

        $('.dz-message').show();
    }
}));