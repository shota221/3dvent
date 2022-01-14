@include('Manual.Manual.Text._content_top', ['title' => '登録内容時利用方法'])
<div class="card-body">
    <p>
        1，登録内容を確認し、以下の「1回換気量の決定」、「呼吸回数の決定」を確認し再設定が必要な場合は「MicroVent®V3の再設定」を押下してください。
    </p>
    <p>
        2，「機器設定値入力」画面に遷移されます。
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '1回換気量の決定'])
<div class="card-body">
    <div>
        <p><b>・推測値VTiが予測値VTi以上だった場合</b><br>→ 設定圧を2.5cmH₂O下げる。（通常10cmH₂O以上）</p>
        <p><b>・推測値VTiが予測値VTi以下だった場合</b><br>→ 設定圧を2.5cmH₂O上げる。（通常35cmH₂O以下）</p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '呼吸回数の決定（1回換気量決定後）'])
<div class="card-body">
    <div>
        <p><b>・呼吸回数を下げる必要がある場合</b><br>→ 供給気量（酸素流量、空気流量）を低下させる。</p>
        <p><b>・呼吸回数を上げる必要がある場合</b><br>→ 供給気量（酸素流量、空気流量）を上昇させる。</p>
    </div>
</div>
@include('Manual.Manual.Text._content_bottom')