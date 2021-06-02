@extends('Admin.page', [
    'asyncable' => true
])

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
    <script src="{{ mix('js/admin/ocr.js') }}"></script>
@stop

{{-- 
/***********************
    TITLE
************************/ 
--}}
@section('title', '帳票回答OCR解析')

{{-- 
/***********************
    HIDDEN
************************/ 
--}}
@section('hidden')

<input 
    type="hidden" 
    id="url-async-html-format" 
    data-method="GET"
    value="{{ route_path('admin.ocr.async.html_format', ['format_id' => 'FORMAT_ID']) }}" />

<input 
    type="hidden" 
    id="url-async-create-queue" 
    data-method="POST"
    value="{{ route_path('admin.ocr.async.queue') }}" />

<input 
    type="hidden" 
    id="url-async-check-queue-status" 
    data-method="GET"
    value="{{ route_path('admin.ocr.async.queue') }}" />
@stop

{{--
/***********************
 CONTENT
************************/
--}}
@section('content')
<div class="container-fluid">
    <h3>帳票回答OCR解析</h3>
    <p>帳票を選択し、回答用紙の画像をアップロードしてください。OCR解析した結果が右に表示されます。<br />結果を確認し、画面下の「登録」をクリックしてください。</p>
    <div class="row">
        <div class="col-md-3">
            <div class="card card-default color-pallete-box">
                <div class="card-body">
                    <form id="setting">
                        <div class="form-group" id="setting-format">
                            <label>帳票選択</label>
                            <div>
                                <select class="form-control" name="format_id" data-allow-clear=true data-searchable=true>
                                @foreach($format_id_name_list as $format_id_name)
                                    <option value="{{ $format_id_name->id }}">{{ $format_id_name->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="setting-file">
                            <label class="clearfix" style="width: 100%;">
                                <span class="inline-block col-sm-9 no-left-padding">画像をアップロード</span><!--
                                --><span class="btn btn-default btn-sm inline-block col-sm-3 reset-file-uploader">
                                    <i class="fa fa-undo-alt"></i>
                                </span>
                            </label>
                            <div id="file-uploader">
                                <div class='dropzone no-margin'>
                                    <input type="file" name="file" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card card-default color-pallete-box">
                <div class="card-body">
                    <form id="content">
                        <div id="load-format-name">
                            <h2 id="format-name" style="margin-bottom: 13px;"></h2>
                        </div>
                        <!-- 帳票ロードエリア -->
                        <div id="load-format-content">
                            <div id="format-content" class="async-load-area"></div>
                        </div>


                    </form>
                </div>
            </div>
        </div>


    </div>
</div>
@stop