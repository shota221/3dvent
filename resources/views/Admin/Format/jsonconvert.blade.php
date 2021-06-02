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
                <h3>帳票管理</h3>
            </div>
            <form action="{{ route('admin.format.json_converter') }}" method="post">
            @csrf
                <div class="card-body">
                    <div class="row justify-content-between mr-1">
                        <h4>コンバータ</h4>
                        <a href="{{ route('admin.format.output_csv_format') }}" class="btn btn-secondary">CSVフォーマット</a>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">入力内容（タブ区切り）</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="9" name="format"></textarea>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">json出力</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="9" name="">@isset($json){{ $json }}@endisset</textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">変換</button>
                    <a href="{{ route('admin.format.list') }}" class="btn btn-secondary btn-block mt-3">帳票管理一覧に戻る</a>
                </div>
            </form>
        </div>
    </div>
@stop
