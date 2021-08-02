@extends('Form.page')

{{-- /***********************
    CSS
************************/ --}}
@section('css')
    <style>
        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background-color: #fff;
            opacity: 0.80;
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

{{-- /***********************
    JS
    ************************/ --}}
@section('js')
@stop

@section('title')
    @lang('messages.form.org_registration_form')
@stop

@section('content')

    <div class="container-fluid">
        <div id = "alert-message"></div>
        <form id="form-content" action="{{ route('form.org_registration_application.create') }}" method="POST"
            name="create">
            @csrf
            <div class="card-body">
                <p>
                    <span class="required"></span>@lang('messages.is_required_item')
                </p>

                {{-- 組織名 --}}
                <div class="form-group">
                    <label for="organization_name">@lang('messages.form.organization_name')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="organization_name" id="organization_name"
                            placeholder='' required>
                    </div>
                </div>

                {{-- 代表者名 --}}
                <div class="form-group">
                    <label for="representative_name">@lang('messages.form.representative_name')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="representative_name" id="representative_name"
                            placeholder='' required>
                    </div>
                </div>

                {{-- 代表者メールアドレス --}}
                <div class="form-group">
                    <label for="representative_email">@lang('messages.form.representative_email')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="representative_email" id="representative_email"
                            placeholder='' required>
                    </div>
                </div>

                {{-- 組織コード --}}
                <div class="form-group">
                    <label for="organization_code">@lang('messages.form.organization_code')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="organization_code" id="organization_code"
                            placeholder='' required>
                    </div>
                </div>
            </div>
        </form>
        <div class="card-footer">
            <div class="form-group">
                <button class="btn btn-small btn-success btn-block" id="async" data-method="create"
                    data-type="POST">@lang('messages.form.send')</button>
            </div>
        </div>
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
@stop
