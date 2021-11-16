@include('Manual.Manual.Text._content_top', ['title' => '登録完了時利用方法'])
<div class="card-body">
    <p>
        1，登録内容を確認し、以下の「1回換気量の決定」、「呼吸回数の決定」を確認し再設定が必要な場合は「機器の設定をやりなおす」を押下してください。
    </p>
    <p>
        2，「機器設定値入力」画面に遷移されます。
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '1回換気量の決定'])
<div class="card-body">
    <div>
        <p><b>・想定値Vtが予測値Vt以上の場合</b><br>→　設定圧を2.5cmH2O下げる。（通常10cmH2O以上）</p>
        <p><b>・想定値Vtが予測値Vt以下の場合</b><br>→　設定圧を2.5cmH2O上げる。（通常35cmH2O以下）</p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '呼吸器回数の決定（1回換気量決定後）'])
<div class="card-body">
    <div>
        <p><b>・呼吸回数を下げる必要がある場合</b><br>→　供給気量（酸素流量、空気流量）を低下させる。</p>
        <p><b>・呼吸回数を上げる必要がある場合</b><br>→　供給気量（酸素流量、空気流量）を上昇させる。</p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')


