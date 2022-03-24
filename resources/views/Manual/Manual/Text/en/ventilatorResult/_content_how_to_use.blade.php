@include('Manual.Manual.Text._content_top', ['title' => 'How to reset MicroVent®V3 and reregistrate'])
<div class="card-body">
    <p>
        1. After tapping the "Vt / MV Estimation and Registration" button, check the "Vt / MV Estimation and Setting Summary". If resetting is required, tap the "MicroVent®V3 Resetting" button.
    </p>
    <p>
        2. You move to the "Ventilator Management" screen, where you can change the Dial Pressure Setting, Air flow rate and Oxygen flow rate again. 
    </p>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => 'How to change the tidal volume'])
<div class="card-body">
    <div>
        <p>1. When adjusting the tidal volume closer to the patient's predicted tidal volume or an appropriate value, essentially keep the total flow rate constant and only slightly change the Set Dial Pressure.</p>

        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">Assuming the total flow rate is the same</th>
            </tr>
            <tr class="bg-light">
                <td>Pressure Dial Setting</td>
                <td class="text-center">Up</td>
                <td class="text-center">Down</td>
            </tr>
            <tr>
                <td class="text-right">Respiratory Rate</td>
                <td class="text-center">↓</td>
                <td class="text-center">↑</td>
            </tr>
            <tr>
                <td class="text-right">Vt(Tidal Volume)</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">MV(Minute Volume)</td>
                <td class="text-center">slightly ↑</td>
                <td class="text-center">slightly ↓</td>
            </tr>
        </table>

        <p>Each adjustment must be made only within the allowance of the respiration rate. Readjustment of the respiratory rate is referred to the next section.
        </p>

        <p>
            2. If estimated Vt exceeds the patient's recommended Vt, decrease the Dial Pressure setting by 2.5 cmH₂O.
        </p>
        <p>
            3. If the estimated Vt is lower than the patient's recommended Vt, increase the Dial Pressure setting by 2.5 cmH₂O.
        </p>
        <p>
            4. Determine whether the respiration rate needs to be readjusted. If so, change the respiratory rate according to the next section.
        </p>

    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => 'How to change the respiration rate'])
<div class="card-body">
    <div>
        <p>
            1. The respiration rate is determined by the lung compliance & airway resistance of the patient, the Dial Pressure Setting, and the total flow rate.
        </p>
        <p>
            2. If the total flow rate changes with the same Dial Pressure Setting, the respiration rate changes with the same Vt(tidal volume). See the below table.
        </p>

        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">Assuming the pressure dial setting is the same</th>
            </tr>
            <tr class="bg-light">
                <td>Total Flow Rate</td>
                <td class="text-center">Up</td>
                <td class="text-center">Down</td>
            </tr>
            <tr>
                <td class="text-right">Respiratory Rate</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">Vt(Tidal Volume)</td>
                <td class="text-center">same</td>
                <td class="text-center">same</td>
            </tr>
            <tr>
                <td class="text-right">MV(Minute Volume)</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
        </table>

        <ul>
            <li>Decreasing the total flow rate: the respiration rate reduces</li>
            <li>Increasing the total flow rate: the respiration rate increases</li>
        </ul>
   
        <p>
            3. When you change the Dial Pressure Setting with the same total flow rate. the respiration rate and Vt (tidal volume) change simultaneously in the opposite direction. See the below table
        </p>
        
        <table class="table table-bordered">
            <tr>
                <th colspan="3" class="text-center bg-light">Assuming the total flow rate is the same</th>
            </tr>
            <tr class="bg-light">
                <td>Pressure Dial Setting</td>
                <td class="text-center">Up</td>
                <td class="text-center">Down</td>
            </tr>
            <tr>
                <td class="text-right">Respiratory Rate</td>
                <td class="text-center">↓</td>
                <td class="text-center">↑</td>
            </tr>
            <tr>
                <td class="text-right">Vt(Tidal Volume)</td>
                <td class="text-center">↑</td>
                <td class="text-center">↓</td>
            </tr>
            <tr>
                <td class="text-right">MV(Minute Volume)</td>
                <td class="text-center">slightly ↑</td>
                <td class="text-center">slightly ↓</td>
            </tr>
        </table>

    </div>
</div>
@include('Manual.Manual.Text._content_bottom')

@include('Manual.Manual.Text._content_top', ['title' => 'How to change FiO₂ of the MicroVent®V3'])
<div class="card-body">
    <div>
        <p>
            1.  Do not set FiO₂ too high to maintain SpO₂ for an extended period. Otherwise, the patient's lung damage may occur. The MV (minute volume ) adjustment must be considered to maintain the high SpO₂ level. How to change the MV, see the previous section.
        </p>
        <p>
            2. The FiO₂ can be easily calculated from the air flow aL/min and the oxygen flow bL/min. Use the following equation to obtain the target FiO₂
        </p>   
        <p>
            3. FiO₂%=100x(ax0.21+b)/(a+b)
        </p>

        <form id="calculate_fio2" name="calculate_fio2" data-method="GET" data-url="{{ guess_route_path('async.calc.fio2') }}">
            <div>
                <div class="form-group">
                    <label for="air_flow">Air flow rate(L/min)</label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="air_flow" 
                        id="air_flow"
                    >
                </div>
                <div class="form-group">
                    <label for="o2_flow">Oxygen flow rate(L/min))</label>
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