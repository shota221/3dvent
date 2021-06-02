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
                <h3>帳票管理</h3>
            </div>
            <form action="{{ route('admin.format.create') }}" method="post" onSubmit="return uploadAlert()">
            @csrf
                <div class="card-body">
                    <div class="row d-flex justify-content-between my-3">
                        <div class="col-sm-4">
                            <h4>帳票新規登録</h4>
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
                            class="form-control" 
                            type="text" 
                            id="InputName" 
                            name="format_name" 
                            value="{{ old('format_name') }}">
                        @isset($error_message->format_name)
                            <small class="text-danger">{{ $error_message->format_name }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label class="d-block">ステータス</label>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="CheckValid" 
                                value="2"
                                {{ old('status') === '2' ? 'checked = "checked"' : '' }}/>
                            <label for="CheckValid" class="form-check-label">使用中</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="CheckTest" 
                                value="1" 
                                {{ old('status') === '0' ? 'checked = "checked"' : '' }}/>
                            <label for="CheckTest" class="form-check-label">テスト</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="status" 
                                id="CheckInvalid" 
                                value="0"
                                {{ old('status') === '0' ? 'checked = "checked"' : '' }}/>
                            <label for="CheckInvalid" class="form-check-label">停止</label>
                        </div>
                        @isset($error_message->status)
                            <br><small class="text-danger">{{ $error_message->status }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label class="d-block">有事平時</label>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="format_type" 
                                id="CheckEmergency" 
                                value="1"  
                                onclick = "checkEmergency()"
                                {{ old('format_type') === '1' ? 'checked = "checked"' : '' }}/>
                            <label for="CheckEmergency" class="form-check-label">有事</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input 
                                class="form-check-input" 
                                type="radio" 
                                name="format_type" 
                                id="CheckPeacetime" 
                                value="2" 
                                onclick = "checkPeacetime()"
                                {{ old('format_type') === '2' ? 'checked = "checked"' : '' }}/>
                            <label for="CheckPeacetime" class="form-check-label">平時</label>
                        </div>
                        @isset($error_message->format_type)
                            <br><small class="text-danger">{{ $error_message->format_type }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="InputLocalgovCode">自治体コード</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="InputLocalgovCode" 
                                name="localgov_code" 
                                placeholder = "ｶﾝﾏ、半角スペース区切り" 
                                value="{{ old('localgov_code') }}"
                                {{ old('format_type') === '2' ? 'disabled = "true"' : '' }}/>
                        @isset($error_message->localgov_code)
                            <small class="text-danger">{{ $error_message->localgov_code }}</small>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">入力内容（json貼付）</label>
                            <textarea 
                                class="form-control" 
                                id="exampleFormControlTextarea1" 
                                rows="9" 
                                name="format">{{ old('format') }}</textarea>
                            @isset($error_message->format)
                                <small class="text-danger">{{ $error_message->format }}</small>
                            @endisset
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">登録</button>
                    <form action="route('admin.format.list')" method="get">
                    <a href="{{ route('admin.format.list') }}" class="btn btn-secondary btn-block mt-3">帳票管理一覧に戻る</a>
                    </form>
                </div>
            </form>
            </div>
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
        function checkPeacetime(){
            if(document.getElementById("InputLocalgovCode").disabled === false){
                document.getElementById("InputLocalgovCode").setAttribute("disabled",true);
            }
        }
        function checkEmergency(){
            if(document.getElementById("InputLocalgovCode").disabled === true){
                document.getElementById("InputLocalgovCode").removeAttribute("disabled");
            }
        }
    </script>
@stop