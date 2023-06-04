@if(\Request::get('p') == '0_2')
    <div class="row col-lg-12 mt-3">
    <table id="business_unit_data" class="table table-striped w-100">
        <thead>
        <tr>
            <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="8">Committee Process</th>
        </tr>
        <tr>
            <th style="vertical-align: middle;border-top:0px;border-bottom:0px;">Committee Name</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Total Decision<br />Not Made</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Total Decision<br />Made</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Approve Onboarding</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Decline Onboarding</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Retain</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Exit</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Retain, and add<br />to watch list HM</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>EB</th>
            <th style="text-align: center">P & R</th>
            <th style="text-align: center">P & R</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['EB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['notmade']}}</td>
                <td style="text-align: center">{{$value['made']}}</td>
                <td style="text-align: center">{{$value['approve']}}</td>
                <td style="text-align: center">{{$value['decline']}}</td>
                <td style="text-align: center">{{$value['retain']}}</td>
                <td style="text-align: center">{{$value['exit']}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>

            </tr>
        @endforeach
        <tr>
            <th>RBB</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['RBB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['notmade']}}</td>
                <td style="text-align: center">{{$value['made']}}</td>
                <td style="text-align: center">{{$value['approve']}}</td>
                <td style="text-align: center">{{$value['decline']}}</td>
                <td style="text-align: center">{{$value['retain']}}</td>
                <td style="text-align: center">{{$value['exit']}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>

            </tr>
        @endforeach
        <tr>
            <th>RB</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['RB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['notmade']}}</td>
                <td style="text-align: center">{{$value['made']}}</td>
                <td style="text-align: center">{{$value['approve']}}</td>
                <td style="text-align: center">{{$value['decline']}}</td>
                <td style="text-align: center">{{$value['retain']}}</td>
                <td style="text-align: center">{{$value['exit']}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>

            </tr>
        @endforeach

        <tr>
            <th>Grand Total</th>
            <th style="text-align: center">{{$totals["notmade"]}}</th>
            <th style="text-align: center">{{$totals["made"]}}</th>
            <th style="text-align: center">{{$totals["approve"]}}</th>
            <th style="text-align: center">{{$totals["decline"]}}</th>
            <th style="text-align: center">{{$totals["retain"]}}</th>
            <th style="text-align: center">{{$totals["exit"]}}</th>
            <th style="text-align: center">{{$totals["watchlist"]}}</th>
        </tr>
        </tbody>
    </table>
</div>

@endif