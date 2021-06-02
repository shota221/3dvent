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

    <script type="text/javascript">
        $(function() {

            $("#search-button").click(function() {
                $("#search-modal").modal();
            });

            $("#search-modal-close,#search-modal-cancel").unbind().click(function() {
                $("#search-modal").modal('hide');
            });
        });

    </script>
@stop

@section('content')

    @isset($alert_message->delete)
        <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong></strong>{{ $alert_message->delete }}
        </div>
    @endisset
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>帳票管理</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>自治体一覧</h4>
                    </div>
                    <div class="row">    
                        <button type="button" class="btn btn-secondary float-right mb-3" id="search-button"><i class="fas fa-search"></i> 絞り込み検索</button>
                        <div class="ml-3 mr-3">
                            <a href="{{ route('admin.format.localgov.register',[$view->format_id]) }}" class="btn btn-primary float-right">追加</a>
                        </div>        
                    </div>
                </div>
                <div class="mb-3">帳票名：{{ $view->format_name }}</div>
                <table class="table table-striped">
                    <tr>
                    <th>自治体</th>
                    <th>削除</th>
                    </tr>
                    @foreach($view->localgov_data as $v) 
                        <tr>
                        <td>{{ $v->ken_name.$v->city_name }}</td>
                        <form action="{{ route('admin.format.localgov.list.delete',[$view->format_id,$v->localgov_id]) }}" id = "form_{{ $v->localgov_id }}" method="post">
                        @csrf
                        @method('delete')
                            <td><a href="#" data-id="{{ $v->localgov_id }}" onclick="deletePost(this);">削除</a></td>
                        </form>
                        </tr>
                    @endforeach 
                </table>
                
                <div class="mt-3">{{ $view->paginator->links('components.pagination') }}</div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.format.list') }}" class="btn btn-secondary btn-block mt-3">帳票管理一覧に戻る</a>
            </div>
            
        </div>
    </div>
    <script>
        function deletePost(e){
            if(window.confirm('削除してもよろしいですか？')){
                document.getElementById('form_' + e.dataset.id).submit();
            }
        }
    </script> 
@stop

@section('modal')
<div class="modal fade" id="search-modal">

    <div class="modal-dialog" style="margin-top: 10%; width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">絞り込み検索</h4>
                <button type="button" class="close" id="search-modal-close" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="height: auto;">
                <form action="{{ route('admin.format.localgov.list',[$view->format_id]) }}" method="get" id="search-form">
                    @csrf
                    <div class="form-group">
                        <label for="InputLocalgovName">自治体名</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            name="localgov_name" 
                            id="InputLocalgovName"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="search-modal-cancel">戻る</button>
                <button type="submit" class="btn btn-primary" form="search-form" id="search-modal-submit">検索</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@stop