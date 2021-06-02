@extends('Admin.page')

{{--
/***********************
CSS
************************/
--}}
@section('css')
@stop

{{--
/***********************
JS
************************/
--}}
@section('js')
    <script src="{{ mix('js/form/form.js') }}"></script>
@stop
@section('content')   
    @isset($alert_message->registration)
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong></strong>{{$alert_message->registration}}
        </div>
    @endisset
    
    @isset ($error_message->global )
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-exclamation-triangle"></i>{{$error_message->global}}</h5>  
        </div>
    @endisset

    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>帳票データ登録</h3>
            </div>
            <form action="{{ route('admin.report_registration.create') }}" method="post">
            @csrf
                <div class="card-body">
                    <div class="row justify-content-between mr-1">
                        <h4>帳票JSONデータ登録</h4>    
                    </div>
                    <div class="form-group">
                        <label for="formatName">対象帳票名</label>
                        <select 
                            class="form-control" 
                            name="format_name" 
                            id="formatName">
                            <option value=""></option>
                            @foreach($view as $format)
                            <option 
                                value="{{ $format->format_name }}" 
                                @if(old('format_name') == $format->format_name) selected @endif>{{ $format->format_name }}</option>
                            @endforeach
                        </select>
                        @isset($error_message->format_name)
                            <div class="text-danger"><small>{{ $error_message->format_name }}</small></div>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="jsonData">帳票回答データ</label>
                        <textarea 
                            class="form-control" 
                            name="report_data" 
                            id="jsonData" 
                            rows="9" 
                            placeholder='[{"last_name":"テスト","first_name":"タロウ",....},{"last_name":"テスト","first_name":"ジロウ",....},{"last_name":"テスト","first_name":"ハナコ",....}]'>{{ old('report_data') }}</textarea>
                            @isset($error_message->report_data)
                                @if(is_array($error_message->report_data))
                                    <div class="text-danger"><small>入力エラー</small></div>
                                @else 
                                    <div class="text-danger"><small>{{ $error_message->report_data }}</small></div>
                                @endif
                            @endisset
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">登録</button>
                </div>
            </form>
        </div>
    </div>
@stop
