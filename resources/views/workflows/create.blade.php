@extends('flow.default')

@section('title') Create Card @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="javascript:void(0)" onclick="saveCardSection()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
            <a href="{{route('card.list')}}" class="btn btn-outline-primary btn-sm mt-3">Back</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    {{Form::open(['url' => route('card.storecard'), 'method' => 'post','class'=>'mt-3 mb-3 form-inline','id'=>'save_card_section_card'])}}
                    <div class="col-md-12">
                        <div class="form-row w-100">
                            <div class="form-group col-md-12 pt-0 pb-0">
                                {{Form::label('name', 'Name',['style'=>'justify-content:left !important;'])}}
                                <div class="col-md-11">
                                    {{Form::text('name',old('name'),['class'=>'form-control form-control-sm w-100'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
                                    @foreach($errors->get('name') as $error)
                                        <div class="invalid-feedback">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                <hr>
                    <div class="col-md-12">
                        {{Form::label('fields', 'Fields')}}
                        <blackboard-cards-editor  ref="section_a"></blackboard-cards-editor>
                    </div>

                {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-js')
    <script>
        $(function () {
            if($(".group_section").val() === 1){
                vm.$refs.section_a.disen(1);
            }

            $(".group_section").on('click',function () {
                vm.$refs.section_a.disen($(this).val());
            });
        });
    </script>
@endsection