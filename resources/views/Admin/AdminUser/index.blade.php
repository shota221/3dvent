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
            <h3>システム管理者管理</h3>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-between my-3">
                <div class="col-sm-4">
                    <h4>システム管理者一覧</h4>
                </div>
                <div class="row">
                    <button type="button" class="btn btn-secondary float-right mb-3" id="search-button"><i class="fas fa-search"></i> 絞り込み検索</button>
                    <div class="ml-3 mr-3">
                        <a href="{{ route('admin.admin_user.registration') }}" class="btn btn-primary float-right">新規登録</a>
                    </div>    
                </div>
            </div>
                <table class="table table-striped">
                    <tr>
                        <th>詳細</th>
                        <th>氏名</th>
                        <th>所属</th>
                        <th>メールアドレス</th>
                        <th>電話番号</th>
                        <th>ステータス</th>
                    </tr>
                    @foreach ($users->paginator as $user)
                        <tr>
                            <td> <a href="{{ route('admin.admin_user.detail', ['id'=>$user->id]) }}"
                                    class="btn btn-primary">詳細</a>&nbsp;
                            </td>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->team }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->phone }}</td>
                            @if( $user->status === trans('message.valid') )
                            <td><div class="badge badge-primary">{{ $user->status }}</div></td>
                            @else
                            <td><div class="badge badge-secondary">{{ $user->status }}</div></td>
                            @endif
                        </tr>
                    @endforeach
                </table>
                <div class="mt-3">{{ $users->paginator->links('components.pagination') }}</div>

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
                <form action="{{  route('admin.admin_user.index')  }}" method="GET" id="search-form">
                    @csrf
                    <div class="form-group">
                        <label for="input-last_name">姓/セイ</label>
                        <input class="form-control" type="text" name="last_name" id="input-last_name" placeholder="すべて">
                    </div>
                    <div class="form-group">
                        <label for="input-first_name">名/メイ</label>
                        <input class="form-control" type="text" name="first_name" id="input-first_name"
                            placeholder="すべて">
                    </div>
                    <div class="form-group">
                        <label for="input-team">所属</label>
                        <input class="form-control" type="text" name="team" id="input-team" placeholder="すべて">
                    </div>
                    <div class="form-group">
                        <label for="input-email">メールアドレス</label>
                        <input class="form-control" type="text" name="email" id="input-email" placeholder="すべて">
                    </div>
                    <div class="form-group">
                        <label for="input-phone">電話番号</label>
                        <input class="form-control" type="text" name="phone" id="input-phone" placeholder="すべて">
                    </div>
                    <div class="form-group">
                        <label class="d-block">ステータス</label>

                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="checkbox"
                                    name="disabled_flg[]" value="0" checked>有効</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label"><input class="form-check-input" type="checkbox"
                                    name="disabled_flg[]" value="1" checked>無効</label>
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
