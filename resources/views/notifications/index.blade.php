@extends('adminlte.default')

@section('title') Notification History @endsection

@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
    <form class="form-inline mt-3">
        Show &nbsp;
        {{Form::text('p',old('p'),['class'=>'form-control form-control-sm','placeholder'=>'Search'])}}
        &nbsp; from &nbsp;
        {{Form::date('f',old('f'),['class'=>'form-control form-control-sm'])}}
        &nbsp; to &nbsp;
        {{Form::date('t',old('t'),['class'=>'form-control form-control-sm'])}}
        <button type="submit" class="btn btn-sm btn-secondary ml-2 mr-2"><i class="fa fa-search"></i> Search</button>
        <a href="{{route('notifications.index')}}" class="btn btn-sm btn-info"><i class="fa fa-eraser"></i> Clear</a>
    </form>

    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="btn-dark">
                <tr>
                    <th>Notification</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            @foreach($notifications as $result)
                <tr @if($result->seen_at != null) @else class="btn-light" @endif>
                    <td><a href="javascript:void(0)" onclick="notify({{$result->sid}})" data-link="{{$result->link}}" class="notlink">{{$result->name}}</a></td>
                    <td>{{\Carbon\Carbon::parse($result->created_at)->format('Y-m-d')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
@endsection

@section('extra-js')
    <script>
        $("a.notlink").on('click',function(e){
            e.preventDefault();
        });

        function notify(id){
            var data = '';
            var links = $("a:focus").attr('data-link');
            data = {
                id: id
            };

            axios.post('/readnotificationshistory',data).then(response => {
               console.log(links);
                window.location.href = links;
            }).catch(error => {
                // todo handle error
            });
        }
    </script>
@endsection
