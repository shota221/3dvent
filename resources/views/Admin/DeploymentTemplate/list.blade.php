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
    <div class="container-fluid">
        <div class="card card-default color-pallete-box">
            <div class="card-header">
                <h3>展開用テンプレート管理</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>展開用テンプレート一覧</h4>
                    </div>
                    <div class="row">    
                        <button type="button" class="btn btn-secondary float-right mb-3" id="search-button"><i class="fas fa-search"></i> 絞り込み検索</button>    
                        <div class="ml-3 mr-3">
                            <a href="{{ route('admin.deployment_template.new') }}" class="btn btn-primary float-right">新規登録</a>
                        </div>    
                    </div>
                </div>
                <table class="table table-striped">
                    <tr>
                    <th>詳細</th>
                    <th>テンプレート名</th>
                    <th>対象帳票名</th>
                    </tr>
                    @foreach($view->paginator as $v)
                        <tr>
                        <td><a href="{{ route('admin.deployment_template.show',$v->id) }}" class="btn btn-primary">詳細</a></td>
                        <td><a href="{{ route('admin.deployment_template.output',$v->id) }}">{{ $v->name }}</a></td>
                        <td>{{ $v->format_name }}</td>
                        </tr>
                    @endforeach 
                </table>
                <div class="mt-3">{{ $view->paginator->links('components.pagination') }}</div>
            </div>
        </div>
    </div>
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
                <form action="{{ route('admin.deployment_template.list') }}" method="GET" id="search-form">
                    @csrf
                    <div class="form-group">
                        <label for="input-name">テンプレート名</label>
                        <input class="form-control" type="text" name="name" id="input-name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="input-format-name">対象帳票名</label>
                        <input class="form-control" type="text" name="format_name" id="input-format-name" placeholder="">
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

