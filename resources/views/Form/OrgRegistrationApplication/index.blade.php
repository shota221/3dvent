@extends('Form.page')

{{-- /***********************
    CSS
************************/ --}}
@section('css')
@stop

{{-- /***********************
    JS
************************/ --}}
@section('js')
    <script src="{{ mix('js/form/index.js') }}"></script>
@stop

@section('title')
    @lang('messages.form.org_registration_form')
@stop

@section('hiddens')
    <input type="hidden" id="has-page-editable-role" value="true" />
@stop

@section('content')

    <!-- 送信完了/入力ミス -->
    @if (Session::has('result'))
        <div class="alert {{ session('result') ? 'alert-success' : 'alert-danger' }} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <strong></strong> {{ session('result') ? trans('messages.sent') : trans('messages.invalid_input') }}
        </div>
    @endif
    <div class="container-fluid">
        <form action="{{ route('form.org_registration_application.create') }}" method="POST">
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
                            placeholder='' value="{{ old('organization_name') }}" required>
                    </div>
                    @if ($errors->has('organization_name'))
                        <small class="text-danger">{{ $errors->first('organization_name') }}</small>
                    @endif
                </div>

                {{-- 代表者名 --}}
                <div class="form-group">
                    <label for="representative_name">@lang('messages.form.representative_name')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="representative_name" id="representative_name"
                            placeholder='' value="{{ old('representative_name') }}"
                            data-validator-types='["required","numeric","positiveNum"]'>
                    </div>
                    @if ($errors->has('representative_name'))
                        <small class="text-danger">{{ $errors->first('representative_name') }}</small>
                    @endif
                </div>

                {{-- 代表者メールアドレス --}}
                <div class="form-group">
                    <label for="representative_email">@lang('messages.form.representative_email')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="representative_email" id="representative_email"
                            placeholder='' value="{{ old('representative_email') }}" required>
                    </div>
                    @if ($errors->has('representative_email'))
                        <small class="text-danger">{{ $errors->first('representative_email') }}</small>
                    @endif
                </div>

                {{-- 組織コード --}}
                <div class="form-group">
                    <label for="organization_code">@lang('messages.form.organization_code')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="organization_code" id="organization_code"
                            placeholder='' value="{{ old('organization_code') }}" required>
                    </div>
                    @if ($errors->has('organization_code'))
                        <small class="text-danger">{{ $errors->first('organization_code') }}</small>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <button class="btn btn-small btn-success btn-block">@lang('messages.form.send')</button>
                </div>
            </div>
        </form>
    </div>
@stop
