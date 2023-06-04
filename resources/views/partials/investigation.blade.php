@if(\Request::get('p') == '0_1')
    <div class="row col-lg-12 mt-3">
        <table id="business_unit_data" class="table table-striped">
            <thead>
            <tr>
                <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="8">Investigation Process</th>
            </tr>
            <tr>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;">Committee Name</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Total population / Inflow</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Out of Scope</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Nett Population<br />for Review</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Not Started</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Started</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Completed<br />Not Submitted</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Completed<br />&amp; Submitted</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>EB</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: center">P & R</th>
                <th style="text-align: center">P & R</th>
            </tr>
            @php
                $total_oos = 0;
                $total_not_started = 0;
                $total_started = 0;
                $total_cns = 0;
                $total_cs = 0;
            @endphp
            @foreach($mi['EB'] as $key => $value)
                <tr>
                    <td class="month">{{$key}}</td>
                    <td style="text-align: center">{{($value['out_of_scope'] + $value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['out_of_scope']}}</td>
                    <td style="text-align: center">{{($value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['not_started']}}</td>
                    <td style="text-align: center">{{$value['started']}}</td>
                    <td style="text-align: center">{{$value['notsubmitted']}}</td>
                    <td style="text-align: center">{{$value['submitted']}}</td>

                </tr>
                @php
                    $total_oos += $value['out_of_scope'];
                    $total_not_started += $value['not_started'];
                    $total_started += $value['started'];
                    $total_cns += $value['notsubmitted'];
                    $total_cs += $value['submitted'];
                @endphp
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
                    <td class="month">{{$key}}</td>
                    <td style="text-align: center">{{($value['out_of_scope'] + $value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['out_of_scope']}}</td>
                    <td style="text-align: center">{{($value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['not_started']}}</td>
                    <td style="text-align: center">{{$value['started']}}</td>
                    <td style="text-align: center">{{$value['notsubmitted']}}</td>
                    <td style="text-align: center">{{$value['submitted']}}</td>

                </tr>
                @php
                    $total_oos += $value['out_of_scope'];
                    $total_not_started += $value['not_started'];
                    $total_started += $value['started'];
                    $total_cns += $value['notsubmitted'];
                    $total_cs += $value['submitted'];
                @endphp
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
                    <td class="month">{{$key}}</td>
                    <td style="text-align: center">{{($value['out_of_scope'] + $value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['out_of_scope']}}</td>
                    <td style="text-align: center">{{($value['not_started'] + $value['started'] + $value['notsubmitted'] + $value['submitted'])}}</td>
                    <td style="text-align: center">{{$value['not_started']}}</td>
                    <td style="text-align: center">{{$value['started']}}</td>
                    <td style="text-align: center">{{$value['notsubmitted']}}</td>
                    <td style="text-align: center">{{$value['submitted']}}</td>

                </tr>
                @php
                    $total_oos += $value['out_of_scope'];
                    $total_not_started += $value['not_started'];
                    $total_started += $value['started'];
                    $total_cns += $value['notsubmitted'];
                    $total_cs += $value['submitted'];
                @endphp
            @endforeach
            <tr>
                <th>Grand Total</th>

                <th style="text-align: center;border-top:0px;">{{($total_oos + $total_not_started + $total_started + $total_cns + $total_cs)}}</td>
                <th style="text-align: center;border-top:0px;">{{$total_oos}}</th>
                <th style="text-align: center;border-top:0px;">{{($total_not_started + $total_started + $total_cns + $total_cs)}}</th>
                <th style="text-align: center;border-top:0px;">{{$total_not_started}}</th>
                <th style="text-align: center;border-top:0px;">{{$total_started}}</th>
                <th style="text-align: center;border-top:0px;">{{$total_cns}}</th>
                <th style="text-align: center;border-top:0px;">{{$total_cs}}</th>

            </tr>
            </tbody>
        </table>
</div>

@endif