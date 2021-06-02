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

    @isset($alert_message->upload)
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong></strong>{{ $alert_message->upload }}
        </div>
    @endisset
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>避難所管理</h3>
            </div>
            <form action="{{ route('admin.shelter.create') }}" method="post" enctype="multipart/form-data" onSubmit="return uploadAlert()">
            @csrf
                <div class="card-body">
                    <div class="row justify-content-between mr-1">
                        <h4>避難所新規登録</h4>
                        <a href="{{ route('admin.shelter.output_csv_format') }}" class="btn btn-secondary">CSVフォーマット</a>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile"></label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input 
                                    type="file" 
                                    id="customFile" 
                                    name="csv_file_data"
                                    class="custom-file-input"
                                    onchange="uploadFile()"
                                >
                                <label class="custom-file-label" for="customFile" data-browse="参照" id="outputFileName">csvファイル選択...</label>
                            </div>
                        </div>
                        @isset( $error_message->csv_file_data )
                            <div class="text-danger"><small>{{ $error_message->csv_file_data }}</small></div>
                        @endisset
                    </div>
                </div>
                                
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="btn">アップロード</button>
                    <a href="{{ route('admin.shelter.list') }}" class="btn btn-secondary btn-block mt-3">避難所管理一覧に戻る</a>
                </div>
            </form>
        </div>
    </div> 

    <script>
        function uploadAlert(){
            if(window.confirm('アップロードしてもよろしいですか？')){
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