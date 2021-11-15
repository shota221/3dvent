@include('Manual.Manual.Text._content_top', ['title' => $title1])
<div class="card-body">
    <p>
        1，MicroVentをテストラングから切り離し、患者の気管チューブに以下の設定のまま接続してください。<br>（設定圧20cm/H20、空気流量9L/分、酸素流量3L/分)
    </p>
    <div>2，MicroVentが動作していることを確認し、以下の初回調整を行ってください。
        <ul>
            <li><i>呼吸数が速すぎるように見える場合</i><br>→ 設定圧を2.5cmH20刻みで増加させ、呼吸回数を減らしてください。</li>
            <li><i>呼吸数が遅すぎるように見える場合</i><br>→ 設定圧を2.5cmH20刻みで低下させ、呼吸回数を増やしてください。</li>
            <li><i>SpO2が92％未満の場合</i><br>→ 供給気量を12L/分まま変えずに、酸素流量の割合を増やしてください。</li>
        </ul>
    </div>
    <p>
        3，空気流量、酸素流量、設定圧をスマートフォンアプリに入力してください。
    </p>
    <p>
        4，3により、FiO2、Estimated peepが表示されます。
    <p>
        5，「呼吸数が正常である」、「SpO2が92％以上である」それぞれにチェックをいれてください。
    </p>
    <p>
        6，「音声測定」もしくは「手動測定はこちら」を押下してください。
    </p>
    <p>
        7，「呼吸器時間測定」の画面に遷移されます。　
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top',['title' => $title2])
<div class="card-body">
    <div>1，以下を参考に設定圧を調整してください。
        <ul>
            <li><i>登録内容の想定値Vtが予測値Vt以上だった場合</i><br>→ 設定圧を2.5cmH2O下げてください。（通常10cmH2O以上）</li>
            <li><i>想定値Vtが予測値Vt以下の場合</i><br>→ 設定圧を2.5cmH2O上げてください。（通常35cmH2O以下）</li>
        </ul>
    </div>
    <p>
        2，設定圧をスマートフォンアプリに入力してください。
    </p>
    <p>
        3，2により、FiO2、Estimated peepが表示されます。
    <p>
        4，「呼吸数が正常である」、「SpO2が92％以上である」それぞれにチェックをいれてください。
    </p>
    <p>
        5，「音声測定」もしくは「手動測定はこちら」を押下してください。
    </p>
    <p>
        6，「呼吸器時間測定」の画面に遷移されます。　
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top',['title' => $title3])
<div class="card-body">
    <div>1，以下を参考に供給気量（酸素流量、空気流量）を調整してください。
        <ul>
            <li><i>呼吸回数を下げる必要がある場合</i><br>→ 供給気量（酸素流量、空気流量）を低下させる</li>
            <li><i>呼吸回数を上げる必要がある場合</i><br>→ 供給気量（酸素流量、空気流量）を上昇させる。</li>
        </ul>
    </div>
    <p>
        2，酸素流量、空気流量をスマートフォンアプリに入力してください。
    </p>
    <p>
        3，2により、FiO2、Estimated peepが表示されます。
    <p>
        4，「呼吸数が正常である」、「SpO2が92％以上である」それぞれにチェックをいれてください。
    </p>
    <p>
        5，「音声測定」もしくは「手動測定はこちら」を押下してください。
    </p>
    <p>
        6，「呼吸器時間測定」の画面に遷移されます。　
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')





