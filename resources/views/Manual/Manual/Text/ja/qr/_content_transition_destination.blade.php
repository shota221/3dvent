@include('Manual.Manual.Text._content_top', ['title' => 'GS1コード読み込み後の設定画面'])
<div class="card-body">
    <div>
        <p><b>・未ログインユーザーもしくはMicroVent®V3所属組織とログインユーザー所属組織に齟齬がある場合</b><br>→「利用規約」画面へ</p>
        <div><b>・ログインユーザーの場合</b>
            <ul>
                <li><i>MicroVent®V3利用患者情報未登録</i><br>→「患者基本情報入力」画面へ</li>
                <li><i>MicroVent®V3利用患者情報登録済み</i><br>→「呼吸器マネージメント」画面へ</li>
            </ul>
        </div>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')



