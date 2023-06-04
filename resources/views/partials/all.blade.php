@if(\Request::get('p') == '0')

    <div class="row mt-3" style="min-width:2300px;">
        <table id="business_unit_data" class="table table-striped table-responsive w-100" style="table-layout: fixed;">
            <thead>
            <tr>
                <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="8">Investigation Process</th>
                <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="7">Committee Process</th>
                <th style="background-color: #79234e !important;border-top:0px;border-bottom:0px;text-align: center;" colspan="5">Closure Process</th>
            </tr>
            <tr>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;width:135px;padding-left: 10px !important;">Committee Name</th>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;width:135px;" colspan="7" class="eb">
                    <table class="table table-condensed table-borderless w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;border-color: transparent !important;margin-left:-1px;border-left: 0px !important;border-style:none !important;">Total population / Inflow</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;border-color: transparent !important;margin-left:-1px;">Out of Scope</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Nett Population<br />for Review</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Not Started</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Started</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Completed<br />Not Submitted</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Completed<br />&amp; Submitted</th>
                        </tr>
                    </table>
                </th>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;width:135px;border-left:5px solid #777777 !important;padding:0px;" colspan="7" class="eb">
                    <table class="table table-condensed table-borderless w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Total Decision<br />Not Made</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Total Decision<br />Made</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Approve Onboarding</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Decline Onboarding</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Retain</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Exit</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Retain, and add<br />to watch list HM</th>
                        </tr>
                    </table>
                </th>
                <th style="vertical-align: middle;border-top:0px;border-bottom:0px;width:135px;border-left:5px solid #777777 !important;" colspan="7" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Added to Watchlist</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Letters Submitted</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Unclaimed funds in Process</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Sent to Collections<br />/ Closed</th>
                            <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">Items Outside 90 days</th>
                        </tr>
                    </table>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="padding-left: 15px !important;">EB</th>
                <th style="vertical-align: middle;border-bottom:0px;width:135px;" colspan="7" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">P & R</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">P & R</th>
                        </tr>
                    </table>
                </th>
                <th style="vertical-align: middle;border-bottom:0px;width:135px;border-left:5px solid #777777 !important;" colspan="7" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">P & R</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;">P & R</th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                        </tr>
                    </table>
                </th>
                <th style="vertical-align: middle;border-bottom:0px;width:135px;border-left:5px solid #777777 !important;" colspan="5" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        <tr>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                <th style="vertical-align: middle;text-align: center;border-top:0px;border-bottom:0px;"></th>
                        </tr>
                    </table>
                </th>
            </tr>
            @php
                $total_oos = 0;
                $total_not_started = 0;
                $total_started = 0;
                $total_cns = 0;
                $total_cs = 0;
            @endphp
            <tr>
            <td colspan="8" class="eb">
                <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
            @foreach($mi1['EB'] as $key => $value)

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
                </table>
            </td>
                <td colspan="7" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
            @foreach($mi2['EB'] as $key => $value)
                <tr>
                    <td style="text-align: center">{{$value['notmade']}}</td>
                    <td style="text-align: center">{{$value['made']}}</td>
                    <td style="text-align: center">{{$value['approve']}}</td>
                    <td style="text-align: center">{{$value['decline']}}</td>
                    <td style="text-align: center">{{$value['retain']}}</td>
                    <td style="text-align: center">{{$value['exit']}}</td>
                    <td style="text-align: center">{{$value['watchlist']}}</td>

                </tr>
                @endforeach
                    </table>
                </td>
                <td colspan="5" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi3['EB'] as $key => $value)
                            <tr>
                                <td style="text-align: center">{{$value['watchlist']}}</td>
                                <td style="text-align: center">{{$value['letter']}}</td>
                                <td style="text-align: center">{{$value['unclaimed']}}</td>
                                <td style="text-align: center">{{$value['collection']}}</td>
                                <td style="text-align: center">{{$value['outside']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <th style="padding-left: 15px !important;">RBB</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="border-left:5px solid #777777 !important;"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="border-left:5px solid #777777 !important;"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td colspan="8" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi1['RBB'] as $key => $value)

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
                    </table>
                </td>
                <td colspan="7" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi2['RBB'] as $key => $value)
                            <tr>
                                <td style="text-align: center">{{$value['notmade']}}</td>
                                <td style="text-align: center">{{$value['made']}}</td>
                                <td style="text-align: center">{{$value['approve']}}</td>
                                <td style="text-align: center">{{$value['decline']}}</td>
                                <td style="text-align: center">{{$value['retain']}}</td>
                                <td style="text-align: center">{{$value['exit']}}</td>
                                <td style="text-align: center">{{$value['watchlist']}}</td>

                            </tr>
                        @endforeach
                    </table>
                </td>
                <td colspan="5" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi3['RBB'] as $key => $value)
                            <tr>
                                <td style="text-align: center">{{$value['watchlist']}}</td>
                                <td style="text-align: center">{{$value['letter']}}</td>
                                <td style="text-align: center">{{$value['unclaimed']}}</td>
                                <td style="text-align: center">{{$value['collection']}}</td>
                                <td style="text-align: center">{{$value['outside']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <th style="padding-left: 15px !important;">RB</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="border-left:5px solid #777777 !important;"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="border-left:5px solid #777777 !important;"></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <td colspan="8" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi1['RB'] as $key => $value)

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
                    </table>
                </td>
                <td colspan="7" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi2['RB'] as $key => $value)
                            <tr>
                                <td style="text-align: center">{{$value['notmade']}}</td>
                                <td style="text-align: center">{{$value['made']}}</td>
                                <td style="text-align: center">{{$value['approve']}}</td>
                                <td style="text-align: center">{{$value['decline']}}</td>
                                <td style="text-align: center">{{$value['retain']}}</td>
                                <td style="text-align: center">{{$value['exit']}}</td>
                                <td style="text-align: center">{{$value['watchlist']}}</td>

                            </tr>
                        @endforeach
                    </table>
                </td>
                <td colspan="5" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                        @foreach($mi3['RB'] as $key => $value)
                            <tr>
                                <td style="text-align: center">{{$value['watchlist']}}</td>
                                <td style="text-align: center">{{$value['letter']}}</td>
                                <td style="text-align: center">{{$value['unclaimed']}}</td>
                                <td style="text-align: center">{{$value['collection']}}</td>
                                <td style="text-align: center">{{$value['outside']}}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
            <tr>
                <th style="padding-left: 15px !important;">Grand Total</th>
                <th colspan="7" class="eb">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                            <tr>
                                <th style="text-align: center;border-top:0px;">{{($total_oos + $total_not_started + $total_started + $total_cns + $total_cs)}}</td>
                                <th style="text-align: center;border-top:0px;">{{$total_oos}}</th>
                                <th style="text-align: center;border-top:0px;">{{($total_not_started + $total_started + $total_cns + $total_cs)}}</th>
                                <th style="text-align: center;border-top:0px;">{{$total_not_started}}</th>
                                <th style="text-align: center;border-top:0px;">{{$total_started}}</th>
                                <th style="text-align: center;border-top:0px;">{{$total_cns}}</th>
                                <th style="text-align: center;border-top:0px;">{{$total_cs}}</th>

                            </tr>
                    </table>
                </th>
                <th colspan="7" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                            <tr>
                                <th style="text-align: center;border-top:0px;">{{$totals2['notmade']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['made']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['approve']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['decline']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['retain']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['exit']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals2['watchlist']}}</th>

                            </tr>
                    </table>
                </th>
                <th colspan="5" class="eb" style="border-left:5px solid #777777 !important;">
                    <table class="table table-condensed w-100" style="table-layout: fixed;margin-bottom:0px;">
                            <tr>
                                <th style="text-align: center;border-top:0px;">{{$totals3['watchlist']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals3['letter']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals3['unclaimed']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals3['collection']}}</th>
                                <th style="text-align: center;border-top:0px;">{{$totals3['outside']}}</th>
                            </tr>
                    </table>
                </th>
            </tr>
            </tbody>
        </table>
    </div>
@endif

