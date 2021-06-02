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

    @isset($alert_message->addition)
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong></strong>{{ $alert_message->addition }}
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
                <h3>帳票管理</h3>
            </div>
            <form action="{{ route('admin.format.localgov.link',$view->id) }}" method="post" onSubmit="return additionAlert()">
            @csrf
                <div class="card-body">
                    <div class="row d-flex justify-content-between my-3">
                        <div class="col-sm-4">
                            <h4>自治体追加</h4>
                        </div>
                    </div>
                    <div class=mb-3>帳票名：{{ $view->name }}</div>
                    <div class="form-group">
                        <label for="InputLocalgovCode">自治体コード</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            name="localgov_code" 
                            id="InputLocalgovCode" 
                            placeholder = "ｶﾝﾏ、半角スペース区切り"
                            value="{{ old('localgov_code') }}"/>
                        @isset( $error_message->localgov_code )
                            <div class="text-danger"><small>{{ $error_message->localgov_code }}</small></div>
                        @endisset
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">追加</button>
                    <a href="{{ route('admin.format.localgov.list',$view->id) }}" class="btn btn-secondary btn-block mt-3">自治体一覧に戻る</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function additionAlert(){
            if(window.confirm('追加してもよろしいですか？')){
                return true;
            }else{
                return false;
            }  
        }
    </script> 
@stop
