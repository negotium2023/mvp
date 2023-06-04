@extends('flow.default')

@section('title') Cards @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="{{route('card.createcard')}}" class="btn btn-primary float-right ml-2 mt-3">Add Card</a>
            {{-- <a href="{{route('card.list')}}" class="btn btn-outline-primary mt-3">Back</a> --}}
        </div>
    </div>
@endsection

@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    <table class="table table-bordered table-sm table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Fields</th>
                            <th class="last">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($card_sections as $section)
                            <tr>
                                <td>{{$section->name}}</td>
                                <td>{{$section->card_section_input()->count()}}</td>
                                
                                <td class="last">
                                    <a href="{{route('card.editcard',$section)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil-alt"></i></a>
                                    <a href="#" onclick="document.querySelector('.deleteform-{{$section->id}}').submit()" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    {{Form::open(['url' => route('card.destroycard',$section), 'method' => 'delete','class'=>'deleteform-'.$section->id])}}
                                    {{Form::close()}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">
                                    <small class="text-muted">No Cards created yet.</small>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection