
@include('Manual.Manual.Text._content_top', ['title' => 'スマートフォンが位置情報の利用を許可していない場合のスマートフォンの設定方法'])
<div class="card-body">
    <div>
        <p>
            <b>iOS</b><br>
            [設定]-[プライバシー]-[位置情報サービス]-[MicroventV3]にて「このAppの使用中のみ許可」にチェックをいれてください。
        </p>
        <p>
            <b>Android</b><br>
            [設定]-[位置情報]-[アプリの権限]-[MicroventV3]にて「アプリの使用中のみ許可」にチェックをいれてください。
        </p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')