@php //$theme = app('App\Theme')->whereUserId(auth()->id())->first(); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{!! asset('storage/favicon.ico') !!}" type="image/x-icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>ATTOOH</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{!! asset('fontawesome/css/all.css') !!}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="{!! asset('css/ionicons.min.css') !!}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{!! asset('adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/custom.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/absa.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/main.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/progress-wizard.min.css') !!}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('css/jquery-ui.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/perfect-scrollbar.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/bootstrap/bootstrap-multiselect.css') !!}">
    <style>
        .ui-tooltip, .arrow:after {
            background: black !important;
            border: 0px solid transparent !important;
        }
        .ui-tooltip {
            padding: 5px 10px;
            color: white !important;
            font-size: 11px !important;
            font-weight:normal !important;
            border-radius: 5px;
            text-transform: uppercase;
            box-shadow: 0 0 7px black;
        }
        .arrow {
            width: 60px;
            height: 14px;
            overflow: hidden;
            position: absolute;
            left: 50%;
            margin-left: 0px;
            bottom: -10px;
        }
        .arrow.top {
            top: -16px;
            bottom: auto;
        }
        .arrow.left {
            left: 20%;
        }
        .arrow:after {
            content: "";
            position: absolute;
            left: 20px;
            top: -20px;
            width: 25px;
            height: 25px;
            box-shadow: 6px 5px 9px -9px black;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        .arrow.top:after {
            bottom: -20px;
            top: auto;
        }

        /* =Tooltip Style -------------------- */

        /* Tooltip Wrapper */
        .has-tooltip {
            position: relative;
        }
        .has-tooltip .tooltip2 {
            opacity: 0;
            visibility: hidden;
            -webkit-transition: visibility 0s ease 0.5s,opacity .3s ease-in;
            -moz-transition: visibility 0s ease 0.5s,opacity .3s ease-in;
            -o-transition: visibility 0s ease 0.5s,opacity .3s ease-in;
            transition: visibility 0s ease 0.5s,opacity .3s ease-in;
        }
        .has-tooltip:hover .tooltip2 {
            opacity: 1;
            visibility: visible;
        }

        /* Tooltip Body */
        .tooltip2 {
            background-color: #222;
            bottom: 130%;
            color: #fff;
            font-size: 14px;
            left: -100%;
            margin-left: 0px;
            padding: 6px;
            position: absolute;
            text-align: left;
            text-decoration: none;
            text-shadow: none;
            width:auto;
            min-width: 600px;
            max-width: 1000px;
            overflow: auto;
            z-index: 4;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            -o-border-radius: 3px;
            border-radius: 3px;
        }

        /* Tooltip Caret */
        .tooltip2:after {
            border-top: 5px solid #222;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            bottom: -5px;
            content: " ";
            font-size: 0px;
            left: 25px;
            line-height: 0%;
            margin-left: -4px;
            position: absolute;
            width: 0px;
            z-index: 1;
        }

        .tooltip2 ol,.tooltip2 ul,.tooltip2 li{
            text-align: left;
            /*margin: 0px;
            padding:0px;*/
        }

        .wrapper, body, html {
            min-height: 100%;
             overflow-x: unset;
        }
    </style>
    @yield('extra-css')
    @if(isset($theme))
        <style>
            body{
                color: #{{$theme->secondary}};
                background: #{{$theme->primary}};
            }
            .sidebar-dark-primary, .support {
                background-color: #{{$theme->sidebar_background}};
            }
            [class*=sidebar-dark] .user-panel {
                border-bottom: 1px solid #{{$theme->active}};
                border-top: 1px solid #{{$theme->active}};
            }
            [class*=sidebar-dark] .brand-link {
                color: #{{$theme->active}};
            }
            .pick-a-color-markup .hex-pound {
                padding-top: 5px;
                background-color: #f4f4f4;
                color: #444;
                border: 1px solid #ddd;
            }
            .sidebar-dark-primary .sidebar a {
                color: #{{$theme->sidebar_text}};
            }
            .sidebar-dark-primary .sidebar a:hover {
                color: #{{$theme->sidebar_text}}!important;
            }
            .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
                background-color: #{{$theme->active}};
            }
            .sidebar .header {
                border-bottom: 1px solid #{{$theme->active}};
                border-top: 1px solid #{{$theme->active}};
            }
            .sidebar .nav-link:after {
                border-bottom: solid 2px #{{$theme->active}};
            }
            .btn-dark{
                color: #{{$theme->primary}}!important;
                background: #{{$theme->secondary}};
                border-color: #{{$theme->secondary}};
            }
            .btn-dark:hover{
                color: #{{$theme->primary}};
                background: #{{$theme->active}};
                border-color: #{{$theme->active}};
            }
            .tabbable .nav-pills .nav-link.active, .tabbable .nav-pills .show>.nav-link, .tabbable .nav-pills > .nav-link:hover {
                color: #{{$theme->primary}};
                background: #{{$theme->secondary}};
                border-color: #{{$theme->secondary}};
            }
            .tabbable .nav-pills > .nav-link {
                border: 1px solid #{{$theme->secondary}};
                color: #{{$theme->secondary}};
                transition: background 0.5s;
            }
            thead th {
                background-color: #{{$theme->secondary}};
                color: #{{$theme->primary}};
            }
            .content-wrapper a {
                color: #{{$theme->secondary}};
                transition: color 0.5s;
            }
            .content-wrapper a:hover{
                color: #{{$theme->active}};
            }
            .completed-region {
                background-color: #{{$theme->secondary}};
                color: #{{$theme->primary}};
            }
            .btn-outline-light {
                color: #fff!important;
            }
            .card-header {
                background-color: #{{$theme->secondary}};
                color: #{{$theme->primary}};
            }
            .btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active, .show>.btn-secondary.dropdown-toggle, .btn-outline-dark:not(:disabled):not(.disabled).active, .btn-outline-dark:not(:disabled):not(.disabled):active, .show>.btn-outline-dark.dropdown-toggle {
                background: #{{$theme->active}};
                border-color: #{{$theme->acive}};
            }
            .btn-info {
                color: #fff!important;
            }
            .table {
                color: #{{$theme->secondary}};
            }
            .sidebar-dark-primary .nav-treeview>.nav-item>.nav-link {
                color: #{{$theme->sidebar_text}};
            }
            .nav > li > a:hover, .nav > li > a:focus {
                background-color: #{{$theme->active}};
            }
            a.btn-outline-primary {
                color: #007bff;
            }
            a.btn-outline-danger {
                color: rgba(177, 31, 36, 1);
            }
            .btn-secondary, .btn-secondary:hover {
                color: #fff!important;
            }
            .nav-tabs {
                border-bottom: 1px solid #{{$theme->secondary}} !important;
            }
            .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
                color: #{{$theme->secondary}};
                background-color: #{{$theme->primary}};
                border-color: #{{$theme->secondary}} #{{$theme->secondary}} #fff;
            }
            .border {
                border: 1px solid #{{$theme->secondary}}!important;
            }
            .border-top-0 {
                border-top: 0!important;
            }
            .nav-tabs > li > a {
                color: #{{$theme->secondary}};
            }
            .btn-success, .btn-danger {
                color: #fff!important;
            }
            .spinner {
                border: 5px solid #{{$theme->secondary}};
                border-right-color: transparent;
            }
            .config-sections ul li a.toggle {
                background-color: #{{$theme->secondary}};
                color: #{{$theme->primary}};
            }
            .config-sections ul li a.toggle:hover {
                background: #{{$theme->active}};
                color: #{{$theme->primary}};
            }
        </style>
    @endif
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition sidebar-mini">
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
<div id="app" class="wrapper">
    @if(auth()->check() && auth()->user()->trial == 1)
        <attooh-trial-notification></attooh-trial-notification>
        <p id="countdown" style="text-align: center; position: absolute;top: 0;left: 0;z-index: 99999;"></p>
    @endif
@include('adminlte.header')
@include('adminlte.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper main-wrapper">
        @yield('header')
        <div class="row flash_msg">
            @if(Session::has('flash_success'))
                <div class="alert alert-success alert-dismissible blackboard-alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <strong>Success!</strong> {{Session::get('flash_success')}}
                </div>
                {{Session::forget('flash_success')}}
            @endif

            @if(Session::has('flash_info'))
                <div class="alert alert-info alert-dismissible blackboard-alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <strong>Notice.</strong> {{Session::get('flash_info')}}
                </div>
            @endif

            @if(Session::has('flash_danger'))
                <div class="alert alert-danger alert-dismissible blackboard-alert">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    <strong>Error!</strong> {{Session::get('flash_danger')}}
                </div>
            @endif
        </div>
        <!-- SETUP WIZARD -->

        <blackboard-wizard></blackboard-wizard>
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
{{--    <aside onmouseover="showScroller(this)" onmouseout="hideScroller(this)" class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>--}}
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-block-down">
            Anything you want
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy; 2018 <a href="https://www.blackboardbs.com">www.blackboardbs.com</a>.</strong> All rights reserved.
    </footer>

    <!-- Bootsrap Modal -->

    <div class="modal fade" id="edit_email_template">
        <div class="modal-dialog" style="width:800px !important;max-width:800px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">View Email Template</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <input type="hidden" class="form-control" name="email_id" id="email_id" >
                            <input type="hidden" class="form-control" name="activity_id" id="activity_id" >
                        </div>
                        <div class="form-group mt-3">
                            {{Form::label('name', 'Name')}}
                            {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Name','id'=>'email_title'])}}

                        </div>

                        <div class="form-group">
                            {{Form::label('Email Body')}}
                            {{ Form::textarea('email_content', null, ['class'=>'form-control my-editor','size' => '30x10','id'=>'email_content']) }}

                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-default btn-sm pull-left" data-dismiss="modal">Close</button>
                            <button type="button" onclick="saveEmailTemplate()" class="btn btn-sm btn-primary">Use Template</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="popup">
        <div class="modal-dialog" style="width:450px !important;max-width:450px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="form-group text-center">
                        {{Form::label('popup', 'Please bring your Blackboard account up to date.')}}

                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Got it</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog" style="width:450px !important;max-width:450px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="form-group text-center">
                        {{Form::label('popup', '',['id'=>'confirmMessage'])}}

                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-sm btn-default" id="confirmOk">Yes</button>
                        <button type="button" class="btn btn-sm btn-default" id="confirmCancel">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmEmailModal">
        <div class="modal-dialog" style="width:550px !important;max-width:550px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="form-group text-left">
                        <input type="hidden" name="client_id" id="confirmEmailClient">
                        <label id='confirmEmailMessage'></label>

                    </div>
                    <div class="form-group text-left all-emails">
                        <ul id='confirmEmails'>

                        </ul>
                    </div>
                    <div class="form-group text-left input-group">
                        {{Form::text('extra-email', '',['class'=>'confirmExtraEmail form-control form-control-sm','size'=>'50','placeholder'=>'Enter email address'])}}
                        <div class="input-group-append-">
                            <a href="javascript:void(0)" class="btn btn-sm btn-secondary add-email">Add</a>
                        </div>

                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-sm btn-default" id="confirmEmailOk">Yes</button>
                        <button type="button" class="btn btn-sm btn-default" id="confirmEmailCancel">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notifyModal">
        <div class="modal-dialog" style="width:450px !important;max-width:450px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="form-group text-center">
                        {{Form::label('popup', '',['id'=>'notifyMessage'])}}

                    </div>
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-sm btn-default" id="notifyOk">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAssignConsultant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title" id="assignconsultantclientname"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <input type="hidden" name="clientid" id="assignconsultantclientid" />
                    <div class="md-form col-sm-12 consultantdetail">

                    </div>
                    <div class="md-form col-sm-12 mb-3">

                    </div>
                    <div class="md-form mb-4 col-sm-12 text-center">
                        <button class="btn btn-sm btn-default" id="assignconsultantsave">Save</button>&nbsp;
                        <button class="btn btn-sm btn-default" id="assignconsultantcancel">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMoveToQA" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title" id="movetoqaclientname"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <input type="hidden" name="clientid" id="movetoqaclientid" />
                    <div class="md-form col-sm-12 movetoqaconsultant">

                    </div>
                    <div class="md-form col-sm-12 mb-3">

                    </div>
                    <div class="md-form mb-4 col-sm-12 text-center">
                        <button class="btn btn-sm btn-default" id="movetoqasave">Save</button>&nbsp;
                        <button class="btn btn-sm btn-default" id="movetoqacancel">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalChangeProcess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 500px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Start New Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="md-form col-sm-12 text-left">
                            <input type="hidden" class="client_id" />
                            <input type="hidden" class="process_id" />
                            <select name="process" class=" chosen-select form-control form-control-sm {{($errors->has('process') ? ' is-invalid' : '')}}" id="move_to_process_new">

                            </select>
                            <div id="move_to_process_new_msg" class="is-invalid"></div>
                        </div>
                        <div class="md-form col-sm-12 text-center">
                            <button class="btn btn-sm btn-default" id="changeprocesssave">Save</button>&nbsp;
                            <button class="btn btn-sm btn-default" id="changeprocesscancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCurrentProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 500px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Current Applications</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="md-form col-sm-12 text-left">
                            <ul id="current_processes" style="padding: 0px 1rem;margin:0px">

                            </ul>
                        </div>
                        <div class="md-form col-sm-12 text-center">
                            <button class="btn btn-sm btn-default" id="currentprocesscancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalClosedProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 500px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Closed Applications</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <div class="row">
                        <div class="md-form col-sm-12 text-left">
                            <ul id="closed_processes" style="padding: 0px 1rem;margin:0px">

                            </ul>
                        </div>
                        <div class="md-form col-sm-12 text-center">
                            <button class="btn btn-sm btn-default" id="closedprocesscancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAllProcesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 700px;">
            <div class="modal-content">
                <div class="modal-header text-center" style="border-bottom: 0px;padding:.5rem;">
                    <h5 class="modal-title">Send for Signatures</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-3">
                    <input type="hidden" id="all_processes_process_id" name="all_processes_process_id">
                    <input type="hidden" id="all_processes_step_id" name="all_processes_step_id">
                    <div class="row">
                        <div class="md-form col-sm-12 text-left">
                            <p class="instruction"></p>
                            <table id="all_processes">

                            </table>
                        </div>
                        <div class="md-form col-sm-12 text-center btn-div">
                            <a href="javascript:void(0)" class="btn btn-secondary btn-sm" id="getApplicationDoc">Submit</a>
                            <button class="btn btn-sm btn-default" id="allprocesscancel">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="{!! asset('js/jquery/jquery.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/jquery/jquery-ui.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/popper.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/bootstrap/bootstrap.min.js') !!}" type="text/javascript"></script>
<!-- AdminLTE -->
<script src="{!! asset('adminlte/dist/js/adminlte.js') !!}" type="text/javascript"></script>
<script src="{!! asset('adminlte/dist/js/jscolor.js') !!}" type="text/javascript"></script>
<!-- OPTIONAL SCRIPTS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" rel="stylesheet"/>

<script src="{!! asset('js/moment.min.js') !!}" type="text/javascript"></script>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=361nrfmxzoobhsuqvaj3hyc2zmknskzl4ysnhn78pjosbik2"></script>
<script src="{!! asset('js/tinymce/vue-tinymce.js') !!}" type="text/javascript"></script>
@auth
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
<!-- SortableJS -->
<script src="https://unpkg.com/sortablejs@1.4.2"></script>
<!-- VueSortable -->
<script src="https://unpkg.com/vue-sortable@0.1.3"></script>
@endauth

<script src="{!! asset('js/highcharts/highcharts.js') !!}"></script>
<script src="{!! asset('js/highcharts/exporting.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/highcharts/grouped-categories.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/highcharts/no-data-to-display.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/highcharts/rounded-corners.js') !!}" type="text/javascript"></script>

<script>
    $(document).ready(function(){


        var active_elem = $(".nav-sidebar").find(".active");

        $(active_elem).parent('li').parent('ul').parent('li').addClass('menu-open');
        //$(active_elem).parent('li').parent('ul').addClass('test');

    });
</script>
<script src="{!! asset('adminlte/dist/js/custom.js') !!}" type="text/javascript"></script>
<script src="{!! asset('chosen/chosen.jquery.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('chosen/docsupport/init.js') !!}" type="text/javascript" charset="utf-8"></script>
<script src="{!! asset('js/autocomplete.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/bootstrap/bootstrap-multiselect.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/perfect-scrollbar.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/main.js') !!}" type="text/javascript"></script>
<script>

    $(document).ready(function()
    {
        $('#admin-menu').on('click',function(e){
            //alert();
            $(".nav-sidebar").find('li.admin-menu').toggle();
        })

        $(".delete").on("click", function(e){
            e.preventDefault();
            if (!confirm("Are you sure you want to delete this record?")){
                return false;
            } else {
                $(this).parents('form:first').submit();
            }

        });

        @if(!strpos($_SERVER['REQUEST_URI'],'progress'))
                $(document).ajaxStart(function() {
                    $('#overlay').fadeIn();
                    });
                $(document).ajaxStop(function() {
                    $('#overlay').fadeOut();
                    });
        @endif
    });
</script>
<script>



$(function(){

    $('[data-toggle="tooltip"]').tooltip({
        items: "[data-original-title]",
        content: function() {
            var element = $( this );
            if ( element.is( "[data-original-title]" ) ) {
                return element.attr( "title" );
            }
        },
        position: {
            my: "center bottom-10", // the "anchor point" in the tooltip element
            at: "center top", // the position of that anchor point relative to selected element
            using: function( position, feedback ) {
                $( this ).css( position );
                $( "<div>" )
                    .addClass( "arrow" )
                    .addClass( feedback.vertical )
                    .addClass( feedback.horizontal )
                    .appendTo( this );
            }
        }
    });

    $('.chosen-select').chosen();
    $('.chosen-select').css('width', '80%');

    $(".step-dropdown").change(function(){

         var url = $('option:selected',this).data('path');
         window.location.href = url;
    });


})

$("#viewprocess").on('change', function () {
    let client_id = $('#client_id').val();
    let process_id = $('#viewprocess').val();
    let step_id = 0;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "GET",
        url: '/clients/getfirststep/' + client_id + '/' + process_id,
        success: function( data ) {
            window.location.href = '/clients/' + client_id + '/progress/' + process_id + '/' + data;
        }
    });
})

@if(auth()->id() == '1000')
$(document).ready(function() {

    if(localStorage.getItem('popState') != 'shown'){
        $("#popup").delay(2000).modal('show');
        localStorage.setItem('popState','shown')
    }

});
@endif
</script>
<script>
    $.fn.extend({
        treed: function (o) {

            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined'){
                if (typeof o.openedClass != 'undefined'){
                    openedClass = o.openedClass;
                }

                if (typeof o.closedClass != 'undefined'){
                    closedClass = o.closedClass;
                }
            };

            //initialize each of the top levels
            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function () {
                var branch = $(this); //li with children ul

                if($(this).find('ul').length > 1) {
                    branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                } else {
                    branch.prepend("<i class='indicator glyphicon glyphicon-chevron-none'></i>");
                }
                branch.addClass('branch');
                branch.on('click', function (e) {

                    if (this == e.target && $(this).find('ul').length > 1) {
                        /*console.log($(this).find('ul').length);*/
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children().children().toggle();
                    }
                })
                /*$(this).children().children().toggle();*/
            });
            //fire event from the dynamically added icon
            tree.find('.branch .indicator').each(function(){
                $(this).on('click', function () {
                    $(this).closest('li').click();
                });
            });
            //fire event to open branch if the li contains an anchor instead of text
            tree.find('.branch>a').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            //fire event to open branch if the li contains a button instead of text
            tree.find('.branch>button').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });

    $('#tree').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});

    function validateEmail(sEmail) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(sEmail)) {
            return false;
        }else{
            return true;
        }
    }

    function getApplicationDoc(client_id,logged_in_user_id, process_id) {
        let clientid = client_id;
        let processid = process_id;



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            /*url: '/clients/submit_for_signature/' + clientid + '/'+processid,*/
            url: '/api/signiflow/getsigniflowdocument/' + clientid + '/' + logged_in_user_id + '/' +processid,
            type: "GET",
            dataType: "json",
            success: function (response) {
                notifyDialog(response.message);
                /*window.location.href = '/storage/documents/processed_applications/'+clientid+'/'+data;*/
            },
            error: function(data) {
                console.log(data.responseText);
            }
        });
    }

    $(function(){
        $('.add-email').on('click',function(){
            let email = $('#confirmEmailModal').find('.confirmExtraEmail').val();
            if(validateEmail(email)) {
                $('#confirmEmailModal').find('.confirmExtraEmail').removeClass('is-invalid');
                $('#confirmEmailModal').find('#confirmEmails').append('<li>'+email+'</li>');
                $('#confirmEmailModal').find('.all-emails').append('<input type="hidden" name="extra-emails[]" value="'+email+'">');
                $('#confirmEmailModal').find('.confirmExtraEmail').val('');
            } else {
                $('#confirmEmailModal').find('.confirmExtraEmail').addClass('is-invalid');
            }
        })

        $('#modalMoveToQA').on('hidden.bs.modal', function () {
            $("#modalMoveToQA").find('#movetoqaclientname').html('');
            $("#modalMoveToQA").find('.movetoqaconsultant').html('');
            $("#modalMoveToQA").find('#movetoqaclientid').val('')
        })

        $('#modalAssignConsultant').on('hidden.bs.modal', function () {
            $("#modalAssignConsultant").find('#assignconsultantclientname').html('');
            $("#modalAssignConsultant").find('.consultantdetail').html('');
            $("#modalAssignConsultant").find('#assignconsultantclientid').val('');
            $("#modalAssignConsultant").find('#assignconsultantcancel').html('');
        })

        $('#changeprocesscancel').on('click',function(){
            $('#modalChangeProcess').modal('hide');
        })

        //move to process depending on radio selection


        $('#assignconsultantcancel').on('click',function(){
            $("#modalAssignConsultant").find('#assignconsultantclientname').html('');
            $("#modalAssignConsultant").find('.consultantdetail').html('');
            $("#modalAssignConsultant").find('#assignconsultantclientid').val('');
            $("#modalAssignConsultant").find('#assignconsultantcancel').html('');
            $("#modalAssignConsultant").modal('hide');
        })



        $('#movetoqacancel').on('çlick',function(){
            $("#modalMoveToQA").modal('hide');
        })

        $('#assignconsultantsave').on('click',function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let client_id = $("#modalAssignConsultant").find('#assignconsultantclientid').val();
            let user_id = $("#modalAssignConsultant").find('#assignconsultantddd').val();

            $.ajax({
                url: '/clients/' + client_id + '/saveconsultant',
                type: "POST",
                dataType: "json",
                data: {clientid:client_id,userid:user_id},
                success: function (data) {
                    $("#modalAssignConsultant").find('#assignconsultantclientname').html('');
                    $("#modalAssignConsultant").find('.consultantdetail').html('');
                    $("#modalAssignConsultant").find('#assignconsultantclientid').val('');
                    $("#modalAssignConsultant").find('#assignconsultantcancel').html('');
                    $("#modalAssignConsultant").modal('hide');

                    if(data.message === 'Success') {
                        $('.flash_msg').html('<div class="alert alert-success">' + data.clname + ' successfully assigned to ' + data.consultant + '</div>');
                    } else {
                        $('.flash_msg').html('<div class="alert alert-danger">An error occured while trying to assign the consultant.</div>');
                    }

                    setTimeout(function(){ window.location.reload(); }, 2000);

                }
            })
        })

        $('#modalRelatedParties').on('hidden.bs.modal', function () {

            $('#modalRelatedParties').find('#relatedmodaldescription').val('');
            $('#modalRelatedParties').find('#relatedmodalfirstname').val('');
            $('#modalRelatedParties').find('#relatedmodallastname').val('');
            $('#modalRelatedParties').find('#relatedmodalidnumber').val('');
            $('#modalRelatedParties').find('#relatedmodalinitials').val('');
            $('#modalRelatedParties').find('#relatedmodalemail').val('');
            $('#modalRelatedParties').find('#relatedmodalcontact').val('');
            $('#modalRelatedParties').find('#relatedmodalparent_chosen').css('border','1px solid #ced4da');
            $('#modalRelatedParties').find('#relatedmodaldescription').removeClass('is-invalid');
        })

        $('#modalRelatedPartiesCopy').on('hidden.bs.modal', function () {
            $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent_chosen').css('border','1px solid #ced4da');
        })

        $("#addrelatedsave").on("click",function(){
            let err = 0;

            let client_id = $('#modalRelatedParties').find('#relatedmodalclientid').val();
            let related_party_id = $('#modalRelatedParties').find('#relatedmodalrelatedid').val();
            let related_party_parent_id = $('#modalRelatedParties').find('#relatedmodalparent').val();
            let description = $('#modalRelatedParties').find('#relatedmodaldescription').val();
            let firstname = $('#modalRelatedParties').find('#relatedmodalfirstname').val();
            let lastname = $('#modalRelatedParties').find('#relatedmodallastname').val();
            let idnumber = $('#modalRelatedParties').find('#relatedmodalidnumber').val();
            let initials = $('#modalRelatedParties').find('#relatedmodalinitials').val();
            let email = $('#modalRelatedParties').find('#relatedmodalemail').val();
            let contact = $('#modalRelatedParties').find('#relatedmodalcontact').val();

            if($('#modalRelatedParties').find('#relatedmodalparent').val() === null){
                err++;
                $('#modalRelatedParties').find('#relatedmodalparent_chosen').css('border','1px solid #dc3545');
            }
            if($('#modalRelatedParties').find('#relatedmodaldescription').val() === ''){
                err++;
                $('#modalRelatedParties').find('#relatedmodaldescription').addClass('is-invalid');
            }

            if(err === 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/relatedparty/save/' + client_id + '/' + related_party_id,
                    type: "POST",
                    data: {
                        client_id: client_id,
                        related_party_id: related_party_id,
                        related_party_parent_id: related_party_parent_id,
                        description: description,
                        firstname: firstname,
                        lastname: lastname,
                        idnumber: idnumber,
                        initials: initials,
                        email: email,
                        contact: contact
                    },
                    success: function (data) {
                        $("#modalRelatedParties").modal('hide');

                        location.reload();
                        /*window.location.href = '/relatedparty/'+client_id;*/
                        /*$('.flash_msg').html('<div class="alert alert-success alert-dismissible blackboard-alert">\n' +
                            '                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>\n' +
                            '                    <strong>Success!</strong> Client successfully unconverted.\n' +
                            '                </div>');*/
                    }
                });
            }
        })

        $("#addrelatedupdate").on("click",function(){
            let err = 0;

            let client_id = $('#modalRelatedParties').find('#relatedmodalclientid').val();
            let related_party_id = $('#modalRelatedParties').find('#relatedmodalrelatedid').val();
            let related_party_parent_id = $('#modalRelatedParties').find('#relatedmodalparent').val();
            let description = $('#modalRelatedParties').find('#relatedmodaldescription').val();
            let firstname = $('#modalRelatedParties').find('#relatedmodalfirstname').val();
            let lastname = $('#modalRelatedParties').find('#relatedmodallastname').val();
            let idnumber = $('#modalRelatedParties').find('#relatedmodalidnumber').val();
            let initials = $('#modalRelatedParties').find('#relatedmodalinitials').val();
            let email = $('#modalRelatedParties').find('#relatedmodalemail').val();
            let contact = $('#modalRelatedParties').find('#relatedmodalcontact').val();

            if($('#modalRelatedParties').find('#relatedmodalparent').val() === null){
                err++;
                $('#modalRelatedParties').find('#relatedmodalparent_chosen').css('border','1px solid #dc3545');
            }
            if($('#modalRelatedParties').find('#relatedmodaldescription').val() === ''){
                err++;
                $('#modalRelatedParties').find('#relatedmodaldescription').addClass('is-invalid');
            }

            if(err === 0) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/relatedparty/update/' + client_id + '/' + related_party_id,
                    method: "POST",
                    data: {
                        client_id: client_id,
                        related_party_id: related_party_id,
                        related_party_parent_id: related_party_parent_id,
                        description: description,
                        firstname: firstname,
                        lastname: lastname,
                        idnumber: idnumber,
                        initials: initials,
                        email: email,
                        contact: contact,
                    },
                    success: function (data) {
                        $("#modalRelatedParties").modal('hide');

                        location.reload();
                        /*window.location.href = '/relatedparty/'+client_id;*/
                        /*$('.flash_msg').html('<div class="alert alert-success alert-dismissible blackboard-alert">\n' +
                            '                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>\n' +
                            '                    <strong>Success!</strong> Client successfully unconverted.\n' +
                            '                </div>');*/
                    }
                });
            }
        })

        $("#addrelatedcopysave").on("click",function(){
            let err =0;

            let client_id = $('#modalRelatedPartiesCopy').find('#copyrelatedmodalclientid').val();
            let related_party_id = $('#modalRelatedPartiesCopy').find('#copyrelatedmodalrelatedid').val();
            let old_related_party_id = $('#modalRelatedPartiesCopy').find('#old_copyrelatedmodalparent').val();
            let related_party_parent_id = $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent').val();

            if($('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent').val() === null){
                err++;
                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent_chosen').css('border','1px solid #dc3545');
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/relatedparty/link/'+client_id+'/'+related_party_id,
                type: "POST",
                data: {client_id: client_id, related_party_id: related_party_id,old_related_party_id: old_related_party_id, related_party_parent_id:related_party_parent_id},
                success: function (data) {
                    $("#modalRelatedPartiesCopy").modal('hide');

                    location.reload();
                }
            });
        })

        $("#relatedcancel").on("click",function(){
            $('#modalRelatedParties').find('#relatedmodalparent_chosen').css('border','1px solid #ced4da');
            $('#modalRelatedParties').find('#relatedmodaldescription').removeClass('is-invalid');
            $("#modalRelatedParties").modal("hide");
        })

        $("#relatedcopycancel").on("click",function(){
            $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent_chosen').css('border','1px solid #ced4da');
            $("#modalRelatedPartiesCopy").modal("hide");
        })

        $(".showHideR").click(function(){
            $(this).parent().parent().parent().find('ul').children().toggle();
        })

        $(".deleterelatedparty").on('click',function(e){
            e.preventDefault();
            let YOUR_MESSAGE_STRING_CONST = "Are you sure you want to completely delete this Related Party from the Client?";

            confirmDialog(YOUR_MESSAGE_STRING_CONST, function(){
                //My code to delete
                window.location = $("#deleterelatedparty").attr('href');
            });
        })
    });

    function confirmDialog(message, onConfirm){
        var fClose = function(){
            modal.modal("hide");
        };

        var fClose2 = function(){
            modal.modal("hide");
            if ($(document).find('#modalSendTemplate').hasClass('show')) {
                $('#modalSendTemplate').find('#sendtemplatecomposeemailsend').attr("disabled", false);
                $('#modalSendTemplate').find('.sendtemplatecancel').attr("disabled", false);
                $('#modalSendTemplate').find('#sendcomposemessage').html('');
                $('#modalSendTemplate').find('#sendtemplatetemplateemailsend').attr("disabled", false);
                $('#modalSendTemplate').modal('hide');
            }
            if ($(document).find('#modalSendDocument').hasClass('show')) {
                $('#modalSendDocument').find('#senddocumentcomposeemailsend').attr("disabled", false);
                $('#modalSendDocument').find('.senddocumentcancel').attr("disabled", false);
                $('#modalSendDocument').find('#sendcomposemessaged').html('');
                $('#modalSendDocument').find('#senddocumenttemplateemailsend').attr("disabled", false);
                $('#modalSendDocument').modal('hide');
            }
            if ($(document).find('#modalSendMA').hasClass('show')) {
                $('#modalSendMA').find('#sendmatemplateemailsend').attr("disabled", true);
                $('#modalSendMA').find('.sendmacancel').attr("disabled", true);
                $('#modalSendMA').find('#sendmamessage').html('');
                $('#modalSendMA').find('#sendmacomposeemailsend').attr("disabled", false);
                $('#modalSendMA').modal('hide');
            }
        };

        var modal = $("#confirmModal");
        modal.modal("show");
        $("#confirmMessage").empty().append(message);
        $("#confirmOk").unbind().one('click', onConfirm).one('click', fClose);
        $("#confirmCancel").unbind().one("click", fClose2);
    }

    function confirmEmailDialog(message, client_id, email, onConfirm){
        var fClose = function(){
            modal.modal("hide");
        };

        var modal = $("#confirmEmailModal");
        modal.modal("show");

        if(email.length > 0){
            $("#confirmEmails").empty().append('<li>'+email+'</li>');
        }

        $('.all-emails').append('<input type="hidden" name="extra-emails[]" value="'+email+'" />');
        $("#confirmEmailClient").val(client_id);
        $("#confirmEmailMessage").empty().append(message);

        $("#confirmEmailOk").unbind().one('click', onConfirm).one('click', fClose);
        $("#confirmEmailCancel").unbind().one("click", fClose);
    }

    function notifyDialog(message){
        var fClose = function(){
            modal.modal("hide");
        };

        var modal = $("#notifyModal");
        modal.modal("show");
        $("#notifyMessage").empty().append(message);
        $("#notifyOk").unbind().one('click', fClose);
    }

    function addRelatedParty(client_id,related_party_id,committee,project,casenr,instruction_date,triggertype) {
        $("#modalRelatedParties").modal("show");
        $('#modalRelatedParties').find('#relatedmodalparent').prop('disabled',false);
        $('#modalRelatedParties').find('.chosen-select').chosen();
        $('#modalRelatedParties').find('.chosen-select').css('width', '90%');
        $('#modalRelatedParties').find('#addrelatedsave').show();
        $('#modalRelatedParties').find('#addrelatedupdate').hide();
        $('#modalRelatedParties').find('#relatedmodalheader').html('Add Related Party');
        $('#modalRelatedParties').find('#relatedmodalclientid').val(client_id);
        $('#modalRelatedParties').find('#relatedmodalrelatedid').val(related_party_id);
        $('#modalRelatedParties').find('#relatedmodalparent').val(related_party_id);
        $('#modalRelatedParties').find('#relatedmodalparent').trigger("chosen:updated");

        $('#modalRelatedParties').find('#relatedmodaldescription').val('');
        $('#modalRelatedParties').find('#relatedmodalfirstname').val('');
        $('#modalRelatedParties').find('#relatedmodallastname').val('');
        $('#modalRelatedParties').find('#relatedmodalinitials').val('');
        $('#modalRelatedParties').find('#relatedmodalidnumber').val('');
        $('#modalRelatedParties').find('#relatedmodalemail').val('');
        $('#modalRelatedParties').find('#relatedmodalcontact').val('');
    }

    function assignConsultant(client_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/clients/'+client_id+'/getdetail',
            type:"GET",
            dataType:"json",
            success:function(data){
                $("#modalAssignConsultant").modal("show");
                if(data.consultant === 0) {
                    $("#modalAssignConsultant").find('#assignconsultantclientname').html('Assign a consultant to '+data.clname);
                    $("#modalAssignConsultant").find('.consultantdetail').html('<select id="assignconsultantddd" class="form-control form-control-sm chosen"></select>');
                    $.each(data.users, function(key, value) {
                        $("#modalAssignConsultant").find('#assignconsultantddd').append($("<option></option>").attr("value",key).text(value));
                    });
                    $("#modalAssignConsultant").find('#assignconsultantddd').trigger("chosen:updated");
                    $("#modalAssignConsultant").find('#assignconsultantclientid').val(client_id);
                    $("#modalAssignConsultant").find('#assignconsultantcancel').html('Cancel');
                    $("#modalAssignConsultant").find('#assignconsultantsave').show();
                } else {
                    $("#modalAssignConsultant").find('#assignconsultantclientname').html('Assigned consultant for '+data.clname);
                    $("#modalAssignConsultant").find('.consultantdetail').html(data.consultant);
                    $("#modalAssignConsultant").find('#assignconsultantclientid').val(client_id);
                    $("#modalAssignConsultant").find('#assignconsultantcancel').html('Close');
                    $("#modalAssignConsultant").find('#assignconsultantsave').hide();
                }
            }
        });


    }

    function manageRelatedParty(related_party_id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/relatedparty/complete/'+related_party_id,
            data: {related_party_id: related_party_id},
            success: function( data ) {
                $("#modalEditComment").modal('show');

                $("#modalRelatedPartiesCopy").modal("show");
                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalheader').html('Manage Parent Related Party');

                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalclientid').val(client_id);
                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalrelatedid').val(related_party_id);

                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent').val(data.parent_ids);
                $('#modalRelatedPartiesCopy').find('#old_copyrelatedmodalparent').val(data.parent_ids);
                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent').trigger("chosen:updated");
                $('#modalRelatedPartiesCopy').find('#copyrelatedmodalparent').trigger("chosen:updated");
            }});
        }

    function deleteRelatedParty(related_party_id,parent_id) {

        let YOUR_MESSAGE_STRING_CONST = "Are you sure you want to delete this Related Party?";

        confirmDialog(YOUR_MESSAGE_STRING_CONST, function(){
            //My code to delete
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: '/relatedparty/delete/'+parent_id+'/'+related_party_id,
                data: {related_party_id: related_party_id},
                success: function( data ) {
                    window.location.reload();
                }});
        });
    }

    function editRelatedParty(client_id,related_party_id,parent_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "GET",
            url: '/relatedparty/edit/'+parent_id+'/'+related_party_id,
            data: {related_party_id: related_party_id},
            success: function( data ) {
                $("#modalEditComment").modal('show');

                $("#modalRelatedParties").modal("show");

                $('#modalRelatedParties').find('#addrelatedsave').hide();
                $('#modalRelatedParties').find('#addrelatedupdate').show();
                $('#modalRelatedParties').find('#relatedmodalheader').html('Edit Related Party');

                $('#modalRelatedParties').find('#relatedmodalclientid').val(client_id);
                $('#modalRelatedParties').find('#relatedmodalrelatedid').val(related_party_id);
                $('#modalRelatedParties').find('#relatedmodaldescription').val(data.description);
                $('#modalRelatedParties').find('#relatedmodalfirstname').val(data.firstname);
                $('#modalRelatedParties').find('#relatedmodallastname').val(data.lastname);
                $('#modalRelatedParties').find('#relatedmodalidnumber').val(data.idnumber);
                $('#modalRelatedParties').find('#relatedmodalinitials').val(data.initials);
                $('#modalRelatedParties').find('#relatedmodalemail').val(data.email);
                $('#modalRelatedParties').find('#relatedmodalcontact').val(data.contact);
                $('#modalRelatedParties').find('#relatedmodalparent').val(data.parent_ids);
                $('#modalRelatedParties').find('#relatedmodalparent').trigger("chosen:updated");
                $('#modalRelatedParties').find('#relatedmodalparent').prop('disabled',true).trigger("chosen:updated");
            }});
    }

    function completeRelatedParty(related_party_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: '/relatedparty/checkactivities/' + related_party_id,
            data: {related_party_id: related_party_id},
            success: function (data) {
                if (data.message === 'Success') {
                    let YOUR_MESSAGE_STRING_CONST = "Are you sure you want to complete the instruction for this Related Party?";

                    confirmDialog(YOUR_MESSAGE_STRING_CONST, function () {
                        $.ajax({
                            type: "POST",
                            url: '/relatedparty/complete/' + related_party_id,
                            data: {related_party_id: related_party_id},
                            success: function (data) {
                                if (data.message === 'Success') {
                                    $('.flash_msg').html('<div class="alert alert-success">Related Party successfully completed</div>');
                                } else {
                                    $('.flash_msg').html('<div class="alert alert-danger">An error occured while trying to complete the instruction.</div>');
                                }

                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            }
                        });
                     });
                } else {
                    let YOUR_MESSAGE_STRING_CONST = "Not all required fields have been captured.";

                    notifyDialog(YOUR_MESSAGE_STRING_CONST);
                }
            }
        });
    }

    function completePrimary(client_id, url) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "POST",
            url: url,
            data: {client_id: client_id},
            success: function (data) {
                if (data.message === 'Success') {
                    let YOUR_MESSAGE_STRING_CONST = "Are you sure you want to complete the instruction for this Primary Client?";

                    confirmDialog(YOUR_MESSAGE_STRING_CONST, function () {

                        $.ajax({
                            type: "POST",
                            url: '/client/complete/' + client_id,
                            data: {client_id: client_id},
                            success: function (data) {
                                if (data.message === 'Success') {
                                    console.log(data)
                                    //$('.flash_msg').html('<div class="alert alert-success">Primary Client successfully completed</div>');
                                } else {
                                    $('.flash_msg').html('<div class="alert alert-danger">An error occured while trying to complete the instruction.</div>');
                                }

                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            }
                        });
                    });
                } else {
                    let YOUR_MESSAGE_STRING_CONST = "Not all required fields have been captured.";

                    notifyDialog(YOUR_MESSAGE_STRING_CONST);
                }
            }
        });
    }

    function moveToQA(client_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/users/qausers/'+client_id,
            type:"GET",
            dataType:"json",
            success:function(data){
                if(data.message === 'not assigned'){
                    let YOUR_MESSAGE_STRING_CONST = "Client can only be moved to QA once it has been assigned.";

                    notifyDialog(YOUR_MESSAGE_STRING_CONST);
                } else {
                    $('#modalMoveToQA').modal('show');
                    $("#modalMoveToQA").find('#movetoqaclientname').html('Move to QA.');
                    $("#modalMoveToQA").find('.movetoqaconsultant').html('<select id="movetoqaconsultantddd" class="form-control form-control-sm chosen"></select>');
                    $.each(data, function (key, value) {
                        $("#modalMoveToQA").find('#movetoqaconsultantddd').append($("<option></option>").attr("value", key).text(value));
                    });
                    $("#modalMoveToQA").find('#movetoqaconsultantddd').trigger("chosen:updated");
                    $("#modalMoveToQA").find('#movetoqaclientid').val(client_id);
                }
            }
        });

    }

    function sendClientEmail(client_id, client_email) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let YOUR_MESSAGE_STRING_CONST;

        if(client_email.length > 0) {
            YOUR_MESSAGE_STRING_CONST = "Are you sure you want to send an email to the following recipients?";
        } else {
            YOUR_MESSAGE_STRING_CONST = "";
        }


        confirmEmailDialog(YOUR_MESSAGE_STRING_CONST, client_id, client_email, function () {
            var emails = $('input[name="extra-emails[]"]').map(function(){
                return this.value;
            }).get();

            var process_id = {{(isset($process_id)?$process_id:'1')}};
            var step_id = {{(isset($step['id'])?$step['id']:'')}};

            $.ajax({
                type: "POST",
                url: '/client/' + client_id +'/sendclientemail',
                data: {client_id: client_id,emails:emails,process_id:process_id,step_id:step_id},
                success: function (data) {
                    if (data.message === 'Success') {
                        $('.flash_msg').html('<div class="alert alert-success">'+data.success_msg+'</div>');
                    } else {
                        $('.flash_msg').html('<div class="alert alert-danger">An error occured while trying to send the email.</div>');
                    }
                    $('.all-emails').empty().append('<ul id=\'confirmEmails\'>\n' +
                        '\n' +
                        '                        </ul>');
                    /*setTimeout(function () {
                        window.location.reload();
                    }, 2000);*/
                }
            });
        });
    }

    (function($) {

        $(".cata-sub-nav").on('scroll', function() {
            $val = $(this).scrollLeft();

            if($(this).scrollLeft() + $(this).innerWidth()>=$(this)[0].scrollWidth){
                $(".nav-next").hide();
            } else {
                $(".nav-next").show();
            }

            if($val == 0){
                $(".nav-prev").hide();
            } else {
                $(".nav-prev").show();
            }
        });
        var w = $('.sidebar').outerWidth(true);
        console.log( 'init-scroll: ' + $(".nav-next").scrollLeft() );
        $(".nav-next").on("click", function(){

            $(".cata-sub-nav").animate( { scrollLeft: '+=460' }, 200);
        });
        $(".nav-prev").on("click", function(){
            $(".cata-sub-nav").animate( { scrollLeft: '-=460' }, 200);
        });



    })(jQuery);
</script>

@yield('extra-js')
<script>
    $(function() {

        var numItems = $('.progress-indicator').find('.card').length;
        if (numItems < parseInt(6)) {
            $('.progress-indicator').addClass('justify-content-center');
        }
        if (numItems === parseInt(6)) {
            $('.progress-indicator').addClass('justify-content-center');
        }
        if (numItems > parseInt(6)) {
            let offs = $('#' + $('#active_step_id').val()).offset().left;
            $('#scrolling-wrapper').animate({scrollLeft: offs - 300}, 0);
        }
    })
</script>
</body>
</html>
