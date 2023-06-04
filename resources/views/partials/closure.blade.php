@if(\Request::get('p') == '0_3')
    <div class="row col-lg-12 mt-3">
    <table id="business_unit_data" class="table table-striped w-100">
        <thead>
        <tr>
            <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="6">Closure Process</th>
        </tr>
        <tr>
            <th style="vertical-align: middle;border-top:0px;border-bottom:0px;">Committee Name</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Added to Watchlist</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Letters Submitted</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Unclaimed funds in Process</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Sent to Collections / Closed</th>
            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Items Outside 90 days</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>EB</th>
            <th style="text-align: center"></th>
            <th style="text-align: center"></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['EB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>
                <td style="text-align: center">{{$value['letter']}}</td>
                <td style="text-align: center">{{$value['unclaimed']}}</td>
                <td style="text-align: center">{{$value['collection']}}</td>
                <td style="text-align: center">{{$value['outside']}}</td>
            </tr>
        @endforeach
        <tr>
            <th>RBB</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['RBB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>
                <td style="text-align: center">{{$value['letter']}}</td>
                <td style="text-align: center">{{$value['unclaimed']}}</td>
                <td style="text-align: center">{{$value['collection']}}</td>
                <td style="text-align: center">{{$value['outside']}}</td>
            </tr>
        @endforeach
        <tr>
            <th>RB</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach($mi['RB'] as $key => $value)
            <tr>
                <td>{{$key}}</td>
                <td style="text-align: center">{{$value['watchlist']}}</td>
                <td style="text-align: center">{{$value['letter']}}</td>
                <td style="text-align: center">{{$value['unclaimed']}}</td>
                <td style="text-align: center">{{$value['collection']}}</td>
                <td style="text-align: center">{{$value['outside']}}</td>
            </tr>
        @endforeach
        <tr>
            <th>Grand Total</th>
            <th style="text-align: center">{{$totals['watchlist']}}</th>
            <th style="text-align: center">{{$totals['letter']}}</th>
            <th style="text-align: center">{{$totals['unclaimed']}}</th>
            <th style="text-align: center">{{$totals['collection']}}</th>
            <th style="text-align: center">{{$totals['outside']}}</th>
        </tr>
        </tbody>
    </table>
</div>
@endif