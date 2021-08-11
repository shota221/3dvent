function utilFormInputParameters($targetForm, dataset) {
    $targetForm[0].reset();
    $.each($targetForm.find('input'), function (i, elm) {
        switch (elm.type) {
            case 'text':
            case 'hidden':
                $(elm).attr('value', dataset[elm.name]);
                break;
            case 'radio':
                $(elm).attr('checked', dataset[elm.name] === elm.value);
                break;
        }
    });
}

function utilFormBuildParameters(elements) {
    var parameters = {};
    var length = elements.length;
    for (var i = 0; i < length; i++) {
        switch (elements[i].type) {
            case 'radio':
                if (elements[i].checked) {
                    parameters[elements[i].name] = elements[i].value;
                }
                break;
            case 'checkbox':
                if (elements[i].checked) {
                    var hasProperty = parameters.hasOwnProperty(elements[i].name);
                    if (! hasProperty) {
                        parameters[elements[i].name] = [];
                    }
                    parameters[elements[i].name].push(elements[i].value);
                }
                break;
            default:
                parameters[elements[i].name] = elements[i].value;
        }
    }
    return parameters;
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
    var errorMessageElements = document.getElementsByClassName('error-message');
    var length = errorMessageElements.length;
    for (var i = 0; i < length; i++) {
        var errorMessageElement = errorMessageElements[0];
        errorMessageElement.remove();
    }
}