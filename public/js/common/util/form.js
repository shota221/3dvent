function utilFormInputParameters($form, data) {
    $form[0].reset();
    $.each($form.find('input, select, textarea'), function (i, elm) {
        $elm = $(elm);

        switch ($elm.prop('tagName')) {
            case 'INPUT':
                switch ($elm.attr('type')) {
                    case 'text':
                    case 'hidden':
                        $(elm).val(data[$elm.attr('name')]);
                        break;
                    case 'radio':
                        $(elm).prop('checked', parseInt($elm.val()) === data[$elm.attr('name')]);
                        break;
                }
                break;
            case 'SELECT':
                if (data[$elm.attr('name')] !== undefined) {
                    $.each($elm.find('option'), function (i, option) {
                        $option = $(option);
                        if (parseInt($option.val()) === data[$elm.attr('name')]) {
                            $elm.val($option.val());
                        }
                    });
                }
                break;
            case 'TEXTAREA':
                $(elm).val(data[$elm.attr('name')]);
        }

    });
}

function utilFormDisplayValidationErrorMessage(errors) {
    errors.forEach(function (error) {
        var key = error.key;
        var message = error.message.translated;
        var errorMessageElement = '<small class="text-danger error-message">' + message + '</small>'
        $(errorMessageElement).insertAfter($('input[name="' + key + '"]'));
    });
}

function utilFormRemoveValidationErrorMessage() {
    $('.error-message').remove();
}
