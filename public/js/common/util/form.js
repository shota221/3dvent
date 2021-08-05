function inputParameters($targetForm, dataset) {
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