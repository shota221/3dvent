
@include('Manual.Manual.Text._content_top', ['title' => $title])
<div class="card-body">
    <div>
        <p>
            <b>・ios</b><br>
            [設定]-[プライバシー]-[位置情報サービス]-[Microvent]にて「このAppの使用中のみ許可」にチェックをいれてください。
        </p>
        <p>
            <b>・android</b><br>
            [設定]-[位置情報]-[アプリの権限]-[Microvent]にて「アプリの使用中のみ許可」にチェックをいれてください。
        </p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')