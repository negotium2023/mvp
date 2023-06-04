@if(\Request::get('p') == '0_4')
    <div class="row col-lg-12 mt-3">
        <table id="business_unit_data" class="table table-striped w-100">
            <thead>
            <tr>
                <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="5">SLA Report</th>
            </tr>
            <tr>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;text-align: center;"><30 Days</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;text-align: center;">30-60 Days</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;text-align: center;">60-90 Days</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;text-align: center;">>90 Days</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="background: #cc0066; color: #FFFFFF"><strong>Not Started</strong></td>
                    <td style="text-align: center">{{$mi["not_started_30_days"]}}</td>
                    <td style="text-align: center">{{$mi["not_started_60_days"]}}</td>
                    <td style="text-align: center">{{$mi["not_started_90_days"]}}</td>
                    <td style="text-align: center">{{$mi["not_started_90_plus_days"]}}</td>
                </tr>
                <tr>
                    <td style="background: #cc0066; color: #FFFFFF"><strong>In Progress</strong></td>
                    <td style="text-align: center">{{$mi["in_progress_30_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_progress_60_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_progress_90_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_progress_90_plus_days"]}}</td>
                </tr>
                <tr>
                    <td style="background: #cc0066; color: #FFFFFF"><strong>In QA</strong></td>
                    <td style="text-align: center">{{$mi["in_qa_30_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_qa_60_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_qa_90_days"]}}</td>
                    <td style="text-align: center">{{$mi["in_qa_90_plus_days"]}}</td>
                </tr>
                <tr>
                    <td style="background: #cc0066; color: #FFFFFF"><strong>In Exit Finalisation</strong></td>
                    <td style="text-align: center">{{$mi["exit_closeout_30_days"]}}</td>
                    <td style="text-align: center">{{$mi["exit_closeout_60_days"]}}</td>
                    <td style="text-align: center">{{$mi["exit_closeout_90_days"]}}</td>
                    <td style="text-align: center">{{$mi["exit_closeout_90_plus_days"]}}</td>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th style="text-align: center">{{($mi["not_started_30_days"] + $mi["in_progress_30_days"] + $mi["in_qa_30_days"] + $mi["exit_closeout_30_days"])}}</th>
                    <th style="text-align: center">{{($mi["not_started_60_days"] + $mi["in_progress_60_days"] + $mi["in_qa_60_days"] + $mi["exit_closeout_60_days"])}}</th>
                    <th style="text-align: center">{{($mi["not_started_90_days"] + $mi["in_progress_90_days"] + $mi["in_qa_90_days"] + $mi["exit_closeout_90_days"])}}</th>
                    <th style="text-align: center">{{($mi["not_started_90_plus_days"] + $mi["in_progress_90_plus_days"] + $mi["in_qa_90_plus_days"] + $mi["exit_closeout_90_plus_days"])}}</th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-center">
                    <h5>SLA Report</h5>
                </div>
                <div class="card-body">
                    <canvas id="slaChart" width="600" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
@endif