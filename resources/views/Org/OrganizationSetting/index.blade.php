@extends('Org.page')

{{--
/***********************
CSS
************************/
--}}
@section('css')
<style>
#overlay{ 
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 10000;
    width: 100%;
    height:100%;
    display: none;
    background-color: #fff; 
    opacity: 0.5;
}
.cv-spinner {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;  
}
.spinner {
    width: 40px;
    height: 40px;
    border: 4px #ddd solid;
    border-top: 4px #2e93e6 solid;
    border-radius: 50%;
    animation: sp-anime 0.8s infinite linear;
}
@keyframes sp-anime {
    100% { 
        transform: rotate(360deg); 
    }
}

</style>
@stop

{{--
/***********************
JS
************************/
--}}
@section('js')
<script>
    $(function() {
        $("#async").click(function(e) {
            e.preventDefault();

            removeValidationErrorMessage();
            removeAlertMessage();
            
            var btn = document.getElementById('async');
            var method = btn.dataset.method; //CRUD  
            var elements = document.forms[method].elements;
            var url = document.forms[method].action;
            var type = btn.dataset.type;
            var message = buildCompletionMessage(type);

            // パラメータ作成
            var parameters = buildParameters(elements);
            
            $(document).ajaxSend(function() {
                showIndicator();　
            });

            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
            $.ajax({
                url        : url,
                type       : type,
                contentType: "application/json",
                data       : JSON.stringify(parameters),
                dataType   : "json",
                timeout    : 120000,
                cache      : false,
            }).done(function(data) {
                alertMessage(message)
            }).fail(function(error) {
                switch (error.status) {
                    case 0:
                        alert('レスポンスタイムアウト。処理は完了している可能性があります。画面をリロードし、確認後再度実行してみてください。');
                        break;
                    case 400:
                        var result = JSON.parse(error.responseText);
                        displayValidationErrorMessage(result.errors);
                        break;
                    case 500:
                        alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
                        break;
                    default:
                        alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
                } 
            }).always(function() {
                hideIndicator();
            });
        });
        
    });

    function showIndicator() {       
        $("#overlay").fadeIn(100);　
    }

    function hideIndicator() {
        setTimeout(function(){
            $("#overlay").fadeOut(100);
        },50);
    }

    function buildParameters(elements) {
        var parameters = {};
            
        for (let element of elements) {
            parameters[element.name] = element.value;
        }

        return parameters;
    }

    function displayValidationErrorMessage (errors) {
        errors.forEach(function (error) {
            var key = error.key;
            var message = error.message.translated;  
            var errorMessageElement =  '<small class="text-danger error-message">' + message + '</small>'
            $(errorMessageElement).insertAfter('#' + key);
        });
    }

    function removeValidationErrorMessage () {
        var errorMessageElements = document.getElementsByClassName('error-message');
        var length = errorMessageElements.length;
        for (var i = 0; i < length; i++) {
            var errorMessageElement = errorMessageElements[0];
            errorMessageElement.remove();
        }
    }

    function alertMessage (message) { 
        var alertMessageElement = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + message + '</strong> </div>'
        $(alertMessageElement).insertAfter('#alert-message');
    }

    function removeAlertMessage () {
        var alertMessageElements = document.getElementsByClassName('alert');
        if (alertMessageElements.length > 0) {
            alertMessageElements[0].remove();
        }  
    }

    function buildCompletionMessage(type) {
        switch (type) {
            case 'put':
                return '更新しました。';
                break;
            case 'post':
                return '登録しました。';
                break;
            case 'delete':
                return '削除しました。';
                break;
            default:
                return '';
        }

    }

</script>

@stop

@section('content')
    <div class="container-fluid">
        <div id = "alert-message"></div>
        <div class="card card-default color-palette-box">
            <div class="card-header">
                <h3>@lang('messages.org.setting')</h3>
            </div>
            <div class="card-body">
                <form id="setting-form" action="{{ route('org.setting.update') }}" name="update">
                    <div>
                        <p class="font-weight-bold">
                            @lang('messages.org.common_setting')
                        </p>
                        <p class="font-weight-bold">
                            <span class="required"></span>&nbsp;@lang('messages.is_required_item')
                        </p>
                        <div class="form-group">
                            <label for="ventilator_value_scan_interval">@lang('messages.org.ventilator_value_scan_interval')<span class="required"></span></label>
                            <input 
                                class="form-control" 
                                type="text" 
                                name="ventilator_value_scan_interval" id="ventilator_value_scan_interval"
                                value={{$setting->ventilator_value_scan_interval}}
                            >
                        </div>
                        <div class="form-group">
                            <label for="vt_per_kg">@lang('messages.org.vt_per_kg')<span class="required"></span></label>
                            <input 
                                class="form-control" 
                                type="text" 
                                name="vt_per_kg" 
                                id="vt_per_kg"
                                value={{$setting->vt_per_kg}}
                            >
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <button 
                        class="btn btn-small btn-success btn-block" 
                        id="async" 
                        data-type="put"
                        data-method="update">@lang('messages.update')
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
@stop


