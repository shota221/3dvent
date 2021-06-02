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
                <h3>帳票管理</h3>
            </div>
            <form action="{{ route('admin.format.update',$view->id) }}" method="post" onSubmit="return updateAlert()">
            @csrf
            @method('put')
                <input type="hidden" name="id" value="{{ $view->id }}">
                <div class="card-body">
                    <div class="row d-flex justify-content-between my-3">
                        <div class="col-sm-4">
                            <h4>帳票詳細</h4>
                        </div>
                        <div class="row">    
                            <div class="ml-3 mr-3">
                                <a href="{{ route('admin.format.json_converter') }}" class="btn btn-primary float-right" target="_blank">コンバータ</a>
                            </div>    
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="InputName">帳票名</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            name="format_name" 
                            id="InputName" 
                            value="{{ $view->name }}"/>
                        @isset( $error_message->format_name )
                            <div class="text-danger"><small>{{ $error_message->format_name }}</small></div>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label class="d-block">ステータス</label>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="checkValid" 
                                value="2" 
                                {{ $view->status == 2 ? 'checked':'' }}/>
                            <label for="checkValid" class="form-check-label">使用中</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="checkTest" 
                                value="1" 
                                {{ $view->status == 1 ? 'checked':'' }}/>
                            <label for="checkTest" class="form-check-label">テスト</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="checkInvalid" 
                                value="0" 
                                {{ $view->status == 0 ? 'checked':'' }}/>
                            <label for="checkInvalid" class="form-check-label">停止</label>
                        </div>
                        @isset($error_message->status)
                            <br><small class="text-danger">{{ $error_message->status }}</small>
                        @endisset
                    </div>
                    <label class="d-block">有事平時</label>
                        <div class="mb-3 ml-3">有事</div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">入力内容（配列フォーマット）</label>
                        <textarea 
                            class="form-control" 
                            id="exampleFormControlTextarea1" 
                            rows="9" 
                            name="format">{{ $view->format }}</textarea>
                        @isset($error_message->format)
                            <small class="text-danger">{{ $error_message->format }}</small>
                        @endisset
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">更新</button>
                    <a href="{{ route('admin.format.list') }}" class="btn btn-secondary btn-block mt-3">帳票管理一覧に戻る</a>
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
@stop
