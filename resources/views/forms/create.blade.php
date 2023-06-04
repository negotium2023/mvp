@extends('flow.default')

@section('title') Create CRM @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="javascript:void(0)" onclick="saveForm()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
            <a href="{{route('forms.index')}}" class="btn btn-outline-primary btn-sm mt-3">Back</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    {{Form::open(['url' => route('forms.store'), 'method' => 'post','class'=>'mt-3 mb-3','files'=>true,'autocomplete' => 'off','id'=>'save_form_form'])}}
                    <div class="form-group">
                        {{Form::label('name', 'Name')}}
                        {{Form::text('name',old('name'),['class'=>'form-control form-control-sm'. ($errors->has('name') ? ' is-invalid' : ''),'placeholder'=>'Name'])}}
                        @foreach($errors->get('name') as $error)
                            <div class="invalid-feedback">
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>

                    {{-- todo notifications --}}

                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@endsection
