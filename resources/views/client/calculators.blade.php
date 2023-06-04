@extends('client.show')

@section('title') Calculators @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>

    </div>
@endsection

@section('tab-content')
    <div class="client-detail">
        <div class="content-container m-0 p-0">
            @yield('header')
            <div class="container-fluid container-content">
                <div class="col p-0 grid-items">

                        <div class="card-group">
                                <div class="col-md-6">
                                    <div class="card p-0 m-0 h-100" style="border: 1px solid #ecf1f4;margin-bottom:0rem !important;">
                                        <div class="d-table" style="width: 100%;">
                                            <div class="grid-icon">
                                                <i class="far fa-file-alt"></i>
                                            </div>
                                            <div class="grid-text">
                                                <span class="grid-heading">Personal and Financial Info Sheet</span>
                                            </div>
                                            <div class="grid-btn">
                                                <a href="{{asset('assets/Personal and Financial Info Sheet.xlsx')}}" class="btn btn-outline-primary btn-block" download>Download</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
@endsection