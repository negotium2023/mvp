<table class="table table-bordered table-sm table-hover" style="border: 1px solid #dee2e6;display: table;border-collapse: collapse">
    <thead>
    <tr>
        <th>P/R</th>
        <th>Name</th>
        <th>Case Nr</th>
        <th>CIF Code</th>
        <th>Committee</th>
        <th>Trigger Type</th>
        @foreach($fields as $key => $val)
            @if($val != null)
                <th>{{$val}}</th>
            @endif
        @endforeach
        <th>Instruction Date</th>
        <th>Assigned User</th>
    </tr>
    </thead>
    <tbody>
    @php $related_parties_count = 0; @endphp
    @forelse($clients as $client)
        @if(isset($client['id']))
            <tr>
                <td class="table100-firstcol">{{$client['type']}}</td>
                <td class="table100-firstcol">{{$client['company']}}</td>
                <td>{{$client['case_nr']}}</td>
                <td>{{$client['cif_code']}}</td>
                <td>{{$client['committee']}}</td>
                <td>{{$client['trigger']}}</td>
                @foreach($client["data"] as $key => $val)
                    <td>@if($val != strip_tags($val)) {!! $val !!} @else {{$val}} @endif</td>
                @endforeach
                <td>{{$client['instruction_date']}}</td>
                <td>{{$client['consultant']}}</td>
            </tr>
        @endif
        @if(isset($client['rp']) && count($client['rp']) > 0)
            @foreach($client['rp'] as $rp)
                <tr class="bg-gray-light">
                    <td>{{$rp['type']}}</td>
                    <td>{{$rp['company']}}</td>
                    <td>{{$rp['case_nr']}}</td>
                    <td>{{$rp['cif_code']}}</td>
                    <td>{{$rp['committee']}}</td>
                    <td>{{$rp['trigger']}}</td>
                    @foreach($rp["data"] as $key => $val)
                        <td>@if($val != strip_tags($val)) {!! $val !!} @else {{$val}} @endif</td>
                    @endforeach
                    <td>{{$rp['instruction_date']}}</td>
                    <td>@if($rp['consultant']['consultant'] != null){{$rp['consultant']['consultant']->first_name}} {{$rp['consultant']['consultant']->last_name}} @endif</td>
                </tr>
            @endforeach
        @endif
    @empty
        <tr>
            <td colspan="100%" class="text-center"><small class="text-muted">No clients match those criteria.</small></td></td>
        </tr>
    @endforelse
    </tbody>
</table>