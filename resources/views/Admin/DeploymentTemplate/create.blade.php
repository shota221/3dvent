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
        <strong></strong>{{ $alert_message->registration }}
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
                <h3>展開用テンプレート管理</h3>
            </div>
            <form action="{{ route('admin.deployment_template.create') }}" method="post" onSubmit="return uploadAlert()" enctype="multipart/form-data">
            @csrf
                <div class="card-body">
                    <div class="col-sm-6">
                        <h4>展開用テンプレート新規登録</h4>
                    </div>
                    <div class="form-group">
                        <label for="input-format-name">対象帳票名</label>
                        <select class="form-control" name="format_name" id="input-format-name">
                            <option value=""></option>
                            @foreach($view as $v)
                            <option value="{{$v}}" @if(old('format_name') == $v) selected @endif>{{$v}}</option>
                            @endforeach
                        </select>
                        @isset( $error_message->format_name )
                            <div class="text-danger"><small>{{ $error_message->format_name }}</small></div>
                        @endisset
                    </div>
                    <label for="customFile">登録用エクセルファイル</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input 
                                type="file" 
                                id="customFile" 
                                name="excel_file_data"
                                class="custom-file-input"
                                onchange="uploadFile()"
                            >
                            <label class="custom-file-label" for="customFile" data-browse="参照" id="outputFileName">エクセルファイル選択...</label>
                        </div>
                    </div>
                    @isset( $error_message->excel_file_data )
                        <div class="text-danger"><small>{{ $error_message->excel_file_data }}</small></div>
                    @endisset
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">登録</button>
                    <a href="{{ route('admin.deployment_template.list') }}" class="btn btn-secondary btn-block mt-3">展開用テンプレート管理一覧に戻る</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function uploadAlert(){
            if(window.confirm('登録してもよろしいですか？')){
                return true;
            }else{
                return false;
            }            
        }
    </script>
    
    <script>
        function uploadFile(){
            let file_name = document.getElementById("customFile").value;
            let regex = /\\|\\/;
            let array = file_name.split(regex);
            console.log(array)
            document.getElementById("outputFileName").innerHTML = array[array.length -1];
        }
    </script>
@stop
