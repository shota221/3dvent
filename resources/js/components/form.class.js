;(function(factory) {
    module.exports = factory( 
        jQuery,
        i18n,
        require('./util/class'),
        require('./util/observable.class'),
        require('./util/validator'),
        require('ladda'),
    );
}(function($, i18n, ClassUtils, Observable, Validator, Ladda) {
    'use strict';

    return ClassUtils.Extend(Observable, function Form($elem) {
        this.$form = $elem;

        this.method = this.$form.attr('method');

        this.action = this.$form.attr('action');
    }, {
        build: function(options) {
            var self = this
                , opts = $.extend({ 
                    editable : true,
                    selectBox: null,
                }, options)
            ;

            // icheck init
            $('input[type="checkbox"]', this.$form)
                .iCheck({ 
                    checkboxClass: 'icheckbox_square-blue' 
                });

            // select init
            $('select', this.$form)
                .selectBox(opts.selectBox);

            // datepicker
            $('.input-group.date', this.$form)
                .daterangepicker({
                    timePicker          : false,
                    singleDatePicker    : true,
                }, function(start) {
                    this.element
                        .find('input')
                            .val(start.format('YYYY-MM-DD'));
                });

            // datetimepicker
            $('.input-group.datetime', this.$form)
                .daterangepicker({
                    timePicker          : true,
                    timePickerIncrement : 10,
                    singleDatePicker    : true,
                }, function(start) {
                    this.element
                        .find('input')
                            .val(start.format('YYYY-MM-DD HH:mm'));
                });

            // input enter key submit制御
            $('input', this.$form)
                .keydown(function(e) {
                    if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                        return false;
                    } else {
                        return true;
                    }
                })
                .attr('autocomplete',   "off")
                .attr('autocorrect',    "off")
                .attr('autocapitalize', "off")
                .attr('spellcheck',     "false");

            (! opts.editable) && this.$form.find('.btn-submit').prop('disabled', true);

            // events
            this.$form
                .on('click.form', '.btn-submit', function() {
                    if (! opts.editable) return false;

                    var deferred        = $.Deferred()
                        , LaddaSubmit   = Ladda.create($(this)[0])
                    ;

                    deferred
                        .done(function() {
                            self.bind({});
                        })
                        .always(function() {
                            LaddaSubmit.stop().remove();
                        });

                    self.clearErrors();

                    LaddaSubmit.start();

                    if ($(this).data('withValidation') && ! self.validate()) {
                        deferred.reject();
                    } else { 
                        self.trigger('submit', deferred, self.getData(), self.action, self.method);
                    }

                    return false;
                })
                .on('click.form', '.btn-cancel', function() {
                    var deferred        = $.Deferred()
                        , LaddaCancel   = Ladda.create($(this)[0])
                    ;

                    deferred.always(function() {
                        LaddaCancel.stop().remove();
                    });

                    self.clearErrors();

                    self.trigger('cancel', deferred);

                    self.bind({});

                    return false;
                })
            
            return this;
        }
        , bind: function(data) {
            // override
        }
        , validate: function() {
            let error = false
                , fieldErrors = []
                , appendTypes = this.validationTypes ? this.validationTypes() : {}
                ;

            this.clearErrors();

            collectFields.call(this).each(function() {
                let $elem           = $(this)
                    , name          = $elem.prop('name')
                    , value         = getFieldValue($elem)
                    , defaultTypes  = $elem.data('validationTypes')
                    , types         = (defaultTypes || []).concat(appendTypes[name] || [])
                    , title         = ($elem.data('validationTitle') || '')
                    , result
                    ;

                result = Validator(value, types, title);

                if (! result.result) {
                    error = true;

                    fieldErrors[name] = [result.message];
                }
            });

            this.handleFieldErrors(fieldErrors);

            if (appendTypes['global']) {
                var result = Validator(null, appendTypes['global'], 'global');

                if (! result.result) {
                    error = true;

                    // TODO global error field
                }
            }

            error && $.ext.notify.warn(i18n('message.invalid_form_inputs'));

            return ! error;
        }
        , handleFieldErrors: function(errors) {
            collectFields.call(this).each(function() {
                let $elem = $(this)
                    , name = $elem.prop('name')
                ;

                errors[name] && createFieldError($elem, errors[name]);
            });

            return this;
        }
        , getData: function() {
            var data = {};

            collectFields.call(this).each(function() {
                let $elem = $(this)
                    , name = $elem.prop('name')
                    , value = getFieldValue($elem)
                ;

                if (null !== value && '' !== value) {
                    if (name.endsWith('[]')) {
                        name = name.replace('[]', '');

                        if (! data[name]) data[name] = [];

                        data[name].push(value); 
                    } else {
                        data[name] = value;
                    }
                 }
            });

            return data;
        }
        , hasError: function() {
            return this.$form.find('.ng-alert').length ? true : false;
        }
        , clearErrors: function(errors) {
            this.$form.find('.ng-alert').remove();

            return this;
        }
        , clear: function() {
            collectFields.call(this).each(function() {
                clearFieldValue($(this));
            });

            return this;
        }
        , destroy: function() {
            this.$form.off('.form');

            // icheck init
            $('input[type="checkbox"]', this.$form).iCheck('destroy');

            // select init
            $('select', this.$form).selectBox('destroy');

            // datepicker
            $('.input-group.date, .input-group.datetime', this.$form).daterangepicker('destroy');

            // input enter key submit制御
            $('input', this.$form).off();

            return this;
        }
    });

    /**
     * field値を取得
     * 
     * @param  {[type]} $field [description]
     * @return {[type]}        [description]
     */
    function getFieldValue($field) {
        var value = '';

        switch ($field.prop('tagName')) {
            case 'INPUT'    : 
            case 'SELECT'   :
            case 'TEXTAREA' :
                if ($field.is(':checkbox')) {
                    value = $field.is(':checked') ? 1 : 0;
                } else 
                if ($field.is(':radio')) {
                    value = $field.filter(function() { return $(this).is(':checked'); }).val();
                } else 
                {
                    value = $field.val();
                }

                break;
        }

        return value;
    }

    /** 
     * field値を空にする
     * 
     * @param  {[type]} $field [description]
     * @return {[type]}        [description]
     */
    function clearFieldValue($field) {
        switch ($field.prop('tagName')) {
            case 'INPUT'    : 
            case 'SELECT'   :
            case 'TEXTAREA' :
                if ($field.is(':checkbox') || $field.is(':radio')) {
                    $field
                        .iCheck('uncheck')
                        .prop('checked', false);
                } else 
                if ($field.is('select')) {
                    $field.val(null).change();
                } else {
                    $field.val('');
                }

                break;
        }
    }

    /** 
     * フィールド入力値を取得
     * @return {[type]} [description]
     */
    function collectFields() {
        let $fields = $();

        for (let property in this) if (property.startsWith('$field')) {
            this[property].each(function() {
                $fields.push($(this)[0]);
            })
        }

        return $fields;
    }

    /**
     * フィールドエラー生成
     * 
     * @param  {[type]} $field [description]
     * @param  {[type]} messages [description]
     * @return {[type]}        [description]
     */
    function createFieldError($field, messages) {
        if (messages) {
            $field.before('<p class="ng-alert">' + messages.join('<br />') + '</p>');
        }
    }
}));