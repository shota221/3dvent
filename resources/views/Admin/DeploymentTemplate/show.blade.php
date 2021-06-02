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

    @isset($alert_message->update)
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong></strong>{{ $alert_message->update }}
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
            <form action="{{ route('admin.deployment_template.update',$view->id) }}" method="post" onSubmit="return updateAlert()" enctype="multipart/form-data">
            @csrf
            @method('put')
                <input type="hidden" name="id" value="{{ $view->id }}">
                <input type="hidden" name="format_name" value="{{ $view->format_name }}">
                <input type="hidden" name="template_name" value="{{ $view->name }}">
                <div class="card-body">
                    <div class="col-sm-6">
                        <h4>展開用テンプレート詳細・編集</h4>
                    </div>
                    <div class="form-group">
                        <label for="InputName">対象帳票名:</label>{{ $view->format_name }}
                    </div>
                    <div class="form-group">
                        <label for="InputName">テンプレート名:</label>{{ $view->name }}
                    </div>
                    <label for="customFile">更新用エクセルファイル</label>
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
                    <button type="submit" class="btn btn-success btn-block">更新</button>
                    <a href="{{ route('admin.deployment_template.list') }}" class="btn btn-secondary btn-block mt-3">展開用テンプレート管理一覧に戻る</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function updateAlert(){
            if(window.confirm('更新してもよろしいですか？')){
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
