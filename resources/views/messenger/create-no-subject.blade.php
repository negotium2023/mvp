@extends('flow.default')
@section('title') New Message @endsection
@section('header')
    <div class="container-fluid container-title">
        <h3>@yield('title')</h3>
        <div class="nav-btn-group">
            <a href="javascript:void(0)" onclick="saveMessage()" class="btn btn-success btn-lg mt-3 ml-2 float-right">Save</a>
            <a href="{{route('messages')}}" class="btn btn-outline-primary btn-sm mt-3 float-right">Back</a>
        </div>
    </div>
@endsection
@section('content')
    <div class="content-container page-content">
        <div class="row col-md-12 h-100 pr-0">
            @yield('header')
            <div class="container-fluid index-container-content">
                <div class="table-responsive h-100">
                    <form action="{{ route('messages.store') }}" method="post" id="message_form">
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">To</label>
                                @if($users->count() > 0)
                                    <select name="recipients" class="form-control form-control-sm select2 chosen-select" multiple>
                                        @foreach($users as $user)

                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>

                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Message Form Input -->
                            <div class="form-group">
                                <label class="control-label">Message</label>
                                <textarea name="message" rows="10" id ="message" class="my-editor form-control form-control-sm">@if(Session::has('page_url')) Hi<br /><br />please have a look at <a href="{{Session::get('page_url')}}">{{Session::get('page_url')}}</a>. @endif</textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-css')
    <link rel="stylesheet" href="{{asset('chosen/chosen.min.css')}}">
@endsection
@section('extra-js')
    <script>
        var editor_config = {
            path_absolute : "/",
            branding:false,
            relative_urls: false,
            convert_urls : false,
            menubar : false,
            selector: "textarea.my-editor",
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            },
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
            relative_urls: false,

            external_filemanager_path:"{{url('tinymce/filemanager')}}/",
            filemanager_title:"Responsive Filemanager" ,
            external_plugins: { "filemanager" : "{{url('tinymce')}}/filemanager/plugin.min.js"}
        };

        tinymce.init(editor_config);
    </script>
@endsection