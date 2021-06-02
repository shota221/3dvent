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
                <h3>帳票管理</h3>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-between my-3">
                    <div class="col-sm-4">
                        <h4>帳票一覧</h4>
                    </div>
                    <div class="row">    
                        <button type="button" class="btn btn-secondary float-right mb-3" id="search-button"><i class="fas fa-search"></i> 絞り込み検索</button>
                        <div class="ml-3 mr-3">
                            <a href="{{ route('admin.format.json_converter') }}" class="btn btn-primary float-right" target="_blank">コンバータ</a>
                        </div>    
                        <div class="mr-3">
                            <a href="{{ route('admin.format.new') }}" class="btn btn-primary float-right">新規登録</a>
                        </div>    
                    </div>
                </div>
                <table class="table table-striped">
                    <tr>
                    <th>詳細</th>
                    <th>帳票名</th>
                    <th>利用自治体</th>
                    <th>有事平時</th>
                    <th>ステータス</th>
                    </tr>
                    @foreach($view->paginator as $v)
                        <tr>
                        <td><a href="{{ route('admin.format.show',$v->id) }}" class="btn btn-primary">詳細</a></td>
                        <td>{{ $v->name }}</td>
                        @if($v->isTypeEmergency)
                            <td><a href="{{ route('admin.format.localgov.list',$v->id) }}">一覧</a></td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ $v->type }}</td>
                        @if($v->isStatusCurrent)
                            <td><small class="badge badge-primary">{{ $v->status }}</small></td>
                        @elseif($v->isStatusTest)
                            <td><small class="badge badge-success">{{ $v->status }}</small></td>
                        @else
                            <td><small class="badge badge-secondary">{{ $v->status }}</small></td>
                        @endif
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
                <form action="{{ route('admin.format.list') }}" method="get" id="search-form">
                    @csrf
                    <div class="form-group">
                        <label for="InputName">帳票名</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            name="name" 
                            id="InputName"/>
                    </div>
                    <div class="form-group">
                        <label class="d-block">有事平時</label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    name="type[]" 
                                    value="1" 
                                    checked/>有事
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    name="type[]" 
                                    value="2" 
                                    checked/>平時
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="d-block">ステータス</label>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    name="status[]" 
                                    value="2" checked/>使用中
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    name="status[]" 
                                    value="1" 
                                    checked/>テスト
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox"
                                    name="status[]" 
                                    value="0" 
                                    checked/>停止
                            </label>
                        </div>
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

