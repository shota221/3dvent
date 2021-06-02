@extends('common.adminlte.page', [
    'nav_left_menu' => 'Admin.left-menu',
    'nav_top_menu'  => 'Admin.top-menu',
    'nav_top_class' => 'navbar-white',
    'nav_left_class' => 'sidebar-dark-primary',
    'asyncable' => (isset($asyncable) ? true : false)
])

@section('title', 'システム管理者用ページ')

@section('role', 'システム管理者')

@section('page_js')
    <script src="{{ mix('js/admin/app.js') }}"></script>

    <script src="{{ mix('js/admin/page.js') }}"></script>

    @yield('js')
@stop

@section('page_css')
    <link rel="stylesheet" href="{{ mix('css/admin/app.css') }}">

    @yield('css')
@stop


@section('page_modal')
    @yield('modal')

    {{-- PROFILE EDIT MODAL --}}

    @component('components.modal', [
        'id'    => 'modal-profile-edit',
        'form'  => [ 'method' => 'PUT', 'action' => guess_route_path('account.async.profile') ],
    ])
        @slot('title')
            プロフィール編集
        @endslot

        @slot('content')
            <tr>
                <th width="180px">姓<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="last_name" 
                        placeholder="山田" 
                        data-validation-types='["required", "length{max:200}"]' 
                        data-validation-title="姓" />
                </td>
            </tr>
            <tr>
                <th>姓カナ<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="last_name_kana" 
                        placeholder="ヤマダ" 
                        data-validation-types='["required", "length{max:200}"]' 
                        data-validation-title="姓カナ" />
                </td>
            </tr>
            <tr>
                <th>名<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="first_name" 
                        placeholder="太郎" 
                        data-validation-types='["required", "length{max:200}"]' 
                        data-validation-title="名" />
                </td>
            </tr>
            <tr>
                <th>名カナ<span class="required"></span></th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="first_name_kana" 
                        placeholder="タロウ" 
                        data-validation-types='["required", "length{max:200}"]' 
                        data-validation-title="名カナ" />
                </td>
            </tr>
            <tr>
                <th>メールアドレス<span class="required"></span>
                    <div class="description">ログインIDとして利用します。</div>
                </th>
                <td>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="email" 
                        placeholder="xxx@yyy.com" 
                        data-validation-types='["required"]' 
                        data-validation-title="メールアドレス" />
                </td>
            </tr>
            <tr>
                <th>ログインパスワード変更<span class="required"></span></th>
                <td>
                    <div class="checkbox icheck no-padding">
                        <input type="checkbox" name="passwordChanged" id="my-password-changed" />
                        <label for="my-password-changed">パスワードを変更する</label>
                    </div>
                    <div class="row password-change-inputs collapse">
                        <div class="col-6">
                            パスワード入力
                            <input 
                                class="form-control" 
                                type="password" 
                                name="password" 
                                placeholder="8文字以上で入力"  
                                data-validation-title="パスワード" />
                        </div>
                        <div class="col-6">
                            パスワード確認
                            <input 
                                class="form-control" 
                                type="password" 
                                name="passwordConfirm" 
                                placeholder="入力確認"
                                data-validation-title="パスワード" />
                        </div>
                    </div>
                </td>
            </tr>
            
        @endslot
    @endcomponent
@stop

@section('page_hidden')
    @yield('hidden')

    <input 
        type="hidden" 
        id="async-data-profile" 
        data-method="GET" 
        value="{{ guess_route_path('account.async.data_profile') }}" />
@stop