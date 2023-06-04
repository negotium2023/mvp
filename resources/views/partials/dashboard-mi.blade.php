<div class="row col-lg-12 mt-3">
    <table class="table table-striped w-100">
        <thead>
        <tr>
            @foreach($consolodated_table_head as $consolodated => $value)

                    <th style="text-align:center;vertical-align: middle;border-top:0px;border-bottom:0px;">{{$value}}</th>

            @endforeach
        </tr>
        </thead>
    @foreach($consolodated_table_body as $consolodated => $value)
        <tr>
            <td>{{$consolodated}}</td>
            @foreach($consolodated_table_body[$consolodated] as $consolodatedvalue)

                    <td align="center">{{$consolodatedvalue}}</td>

            @endforeach
        </tr>
    @endforeach
    </table>
    <span style="width: 100%;">SLA - 30 days from instruction</span>
    <h4>Total outside SLA {{$consolodated_outside_sla}}</h4>
</div>
<hr class="col-sm-12">
<div class="row col-lg-12 mb-3">
    <div class="col-sm-6">
        <div id="out_sla" style="margin: 0 auto"></div>
    </div>
    <div class="col-sm-6">
        <table id="business_unit_data" class="table table-striped w-100">
            <thead>
            <tr>
                <th style="background-color: transparent !important;border-top:0px;border-bottom:0px;"></th>
                <th colspan="4" style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;background-color: #993366 !important;">WIP</th>
                <th colspan="2" style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;background-color: #993366 !important;">Completed</th>
                {{--<th rowspan="2" style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;background-color: #993366 !important;">Grand<br /> Total</th>--}}
            </tr>
            <tr>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;">Business Unit</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Not<br /> Started</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">In<br /> Progress</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">In QA</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Pack<br /> Submitted</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Retain</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Exit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($outside_sla as $key=>$value)
                <tr class="dataRow">
                    <td>{{ $key }}</td>
                    @foreach($value as $value2)
                    <td style="text-align: center">{{ $value2 }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>



