@include('Manual.Manual.Text._content_top', ['title' => 'MicroVent®V3再設定と再登録の方法'])
<div class="card-body">
    <p>
        1. "Vt / MV推定値と登録"後、"Vt / MVの推定値と設定サマリー "で確認します。再設定が必要な場合は、"MicroVent®V3設定"をタップします。
    </p>
    <p>
        2. 呼吸器マネジメント画面に遷移し、そこで再度設定圧、空気流量、酸素流量を変更します。
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '1回換気量の再設定方法'])
<div class="card-body">
    <div>
        <p>1. 1回換気量は、合計流量にかかわらず、肺コンプライアンス、気道抵抗と設定圧によって決定されます。1回換気量を調整する時は、最初は合計流量を一定にして、設定圧のみすこし変化させ、1回換気量を近づけます。</p>

        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">合計流量を変えない場合</th>
            </tr>
            <tr class="bg-light">
                <td>設定圧</td>
                <td class="text-center">アップ</td>
                <td class="text-center">ダウン</td>
            </tr>
            <tr>
                <td class="text-right">呼吸数</td>
                <td class="text-center">↓</td>
                <td class="text-center">↑</td>
            </tr>
            <tr>
                <td class="text-right">1回換気量</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">分時換気量</td>
                <td class="text-center">やや↑</td>
                <td class="text-center">やや↓</td>
            </tr>
        </table>

        <p>1回の調整は呼吸数が許容範囲を超えない程度にします。呼吸数の再調整は次項を参照します。</p>

        <p>
            2. Vt推定値が患者の推奨Vt値を超え、下げる必要が有る場合:ダイヤル設定圧を2.5cmH₂O下げる
        </p>
        <p>
            3. Vt推定値が患者の推奨Vt値より低く、上げる必要が有る場合:ダイヤル設定圧を2.5cmH₂O上げる
        </p>
        <p>
            4. 呼吸数の再調整が必要かどうか判断する必要があれば、次項の呼吸回数の再設定をおこないます。
        </p>

    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => '呼吸回数の再設定方法'])
<div class="card-body">
    <div>
        <p>
            1. 呼吸回数は患者の肺コンプライアンスと気道抵抗、設定圧、合計流量によって決定されます。
        </p>
        <p>
            2. 設定圧を変えずに、合計流量を調整する場合は1回換気量は変わらずに、呼吸回数のみが調整できます。表を参照
        </p>

        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">設定圧を変えない場合</th>
            </tr>
            <tr class="bg-light">
                <td>合計流量</td>
                <td class="text-center">アップ</td>
                <td class="text-center">ダウン</td>
            </tr>
            <tr>
                <td class="text-right">呼吸数</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">1回換気量</td>
                <td class="text-center">不変</td>
                <td class="text-center">不変</td>
            </tr>
            <tr>
                <td class="text-right">分時換気量</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
        </table>

        <ul>
            <li>合計流量を低下させる<br>→呼吸回数が低下する</li>
            <li>合計流量を上昇させる<br>→呼吸回数が上昇する</li>
        </ul>
   
        <p>
            3. 合計流量を変えずに、設定圧を調整する場合は、呼吸回数と1回換気量の両者が反対方向に変化します。表を参照↓
        </p>
        
        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">合計流量を変えない場合</th>
            </tr>
            <tr class="bg-light">
                <td>設定圧</td>
                <td class="text-center">アップ</td>
                <td class="text-center">ダウン</td>
            </tr>
            <tr>
                <td class="text-right">呼吸数</td>
                <td class="text-center">↓</td>
                <td class="text-center">↑</td>
            </tr>
            <tr>
                <td class="text-right">1回換気量</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">分時換気量</td>
                <td class="text-center">やや↑</td>
                <td class="text-center">やや↓</td>
            </tr>
        </table>

    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => 'FiO₂の再設定方法'])
<div class="card-body">
    <div>
        <p>
            1. SpO₂を維持するためにFiO₂を高めすぎないためのコツ
        </p>
        <ul>
            <li>FiO₂を高濃度（50%以上）に長時間設定すると肺損傷をおこします。高濃度にせず、SpO₂を維持するためには、FiO₂に頼るだけでなく、分時換気量を増加させることが必要です。</li>
            <li>分時換気量を増加させるためには、前項の呼吸回数の再設定を参照します。</li>
        </ul>

        <p>
            2. MicroVent®V3のFiO₂は空気流量aL/minと酸素流量bL/minから容易に計算できます。
        </p>   
        <p>
            3. 以下を使い、希望するFiO₂を設定します。FiO₂%=100x(ax0.21+b)/(a+b)
        </p>

        <form id="calculate_fio2" name="calculate_fio2" data-method="GET" data-url="{{ guess_route_path('async.calc.fio2') }}">
            <div>
                <div class="form-group">
                    <label for="air_flow">空気流量(L/min)</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="air_flow" 
                        id="air_flow"
                    >
                </div>
                <div class="form-group">
                    <label for="o2_flow">酸素流量(L/min)</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="o2_flow" 
                        id="o2_flow"
                    >
                </div>
                <p><b>FiO₂: <span id="fio2">**.*</span> %</b></p>
            </div>
        </form>

    </div>
</div>
@include('Manual.Manual.Text._content_bottom')