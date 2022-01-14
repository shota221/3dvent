@include('Manual.Manual.Text._content_top', ['title' => 'Screen after GS1 code reading'])
<div class="card-body">
    <div>
        <p><b>・未ログインユーザーもしくはMicroVent所属組織とログインユーザー所属組織に齟齬がある場合</b><br>→「利用規約」画面に遷移</p>
        <div><b>・ログインユーザーの場合</b>
            <ul>
                <li><i>MicroVent利用患者情報未登録</i><br>→「患者基本情報入力」画面に遷移</li>
                <li><i>MicroVent利用患者情報登録済み</i><br>→「機器設定値入力」画面に遷移</li>
            </ul>
        </div>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')



