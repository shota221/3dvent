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

    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>避難所管理</h3>
            </div>
            <form action="{{ route('admin.shelter.update',$view->id) }}" method="post" onSubmit="return updateAlert()">
            @csrf
            @method('put')
                <input type="hidden" name="shelter_id" value="{{ $view->id }}">
                <div class="card-body">
                    <div class="col-sm-4">
                        <h4>避難所詳細</h4>
                    </div>
                    <div class="form-group">
                        <label for="InputName">避難所名</label>
                        <input type="text" class="form-control" id="InputName" name="shelter_name" value="{{ $view->name }}">
                        @isset( $error_message->shelter_name )
                            <div class="text-danger"><small>{{ $error_message->shelter_name }}</small></div>
                        @endisset

                    </div>
                    <div class="form-group">
                        <label for="InputAddress">住所</label>
                        <input type="text" class="form-control" id="InputAddress" name="shelter_address" value="{{ $view->address }}">
                        @isset( $error_message->shelter_address )
                            <div class="text-danger"><small>{{ $error_message->shelter_address }}</small></div>
                        @endisset
                    </div>
                    <div class="form-group">
                        <label for="InputEmail1">ステータス</label>
                        @if(!$view->disable_flg)
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="shelter_disable_flg" 
                                    id="check_valid"
                                    value="0" 
                                    checked=""
                                >
                                <label for="check_valid" class="form-check-label">有効</label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="shelter_disable_flg" 
                                    id="check_invalid"
                                    value="1" 
                                >
                                <label for="check_invalid" class="form-check-label">無効</label>
                            </div>
                        @else
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="shelter_disable_flg" 
                                    id="check_valid"
                                    value="0" 
                                >
                                <label for="check_valid" class="form-check-label">有効</label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="shelter_disable_flg" 
                                    id="check_invalid"
                                    value="1" 
                                    checked=""
                                >
                                <label for="check_invalid" class="form-check-label">無効</label>
                            </div>
                        @endif
                        @isset( $error_message->shelter_disable_flg )
                            <div class="text-danger"><small>{{ $error_message->shelter_disable_flg }}</small></div>
                        @endisset
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">更新</button>
                    <a href="{{ route('admin.shelter.list') }}" class="btn btn-secondary btn-block mt-3">避難所管理一覧に戻る</a>
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
