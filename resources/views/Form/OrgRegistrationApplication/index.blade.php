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
@stop

@section('title')
    @lang('messages.form.org_registration_form')
@stop

@section('content')

    <div class="container-fluid">
        <form id="form-content" action="{{ route('form.org_registration_application.create') }}" method="POST">
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
                            placeholder=''  required>
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
                            placeholder=''  required>
                    </div>
                </div>

                {{-- 組織コード --}}
                <div class="form-group">
                    <label for="organization_code">@lang('messages.form.organization_code')<span
                            class="required"></span></label>
                    <div>
                        <input class="form-control" type="text" name="organization_code" id="organization_code"
                            placeholder=''  required>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group">
                    <button type="button"
                        class="btn btn-small btn-success btn-block btn-submit">@lang('messages.form.send')</button>
                </div>
            </div>
        </form>
    </div>
@stop
