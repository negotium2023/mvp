<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="{!! asset('css/main.css') !!}">
</head>
<body>
<nav class="navbar navbar-expand-lg container-fluid shadow text-uppercase">
    <a class="navbar-brand" href="{{ route('home') }}">At a glance</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Financial planning<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">My stuff</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">My practice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">My finance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">My training</a>
            </li>

        </ul>

        <ul class="nav justify-content-end">
            <li class="nav-item">
                <form class="form-inline my-2 my-lg-0">
                    <div class="input-group nav-search">
                        <input class="form-control shadow rounded-pill py-2 pr-5 mr-1 bg-transparent" type="search" placeholder="search" id="global-search">
                        <span class="input-group-append">
                            <div class="input-group-text border-0 bg-transparent ml-n5"><i class="fa fa-search"></i></div>
                        </span>
                    </div>
                </form>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><img src="{!! asset('img/Building.svg') !!}" alt="Building"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><img src="{!! asset('img/user.svg') !!}" alt="User"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><img src="{!! asset('img/rocket.svg') !!}" alt="Rocket"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><img src="{!! asset('img/alarm-clock.svg') !!}" alt="Alarm Clock"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><img src="{!! asset('img/bell.svg') !!}" alt="Bell"></a>
            </li>
        </ul>

    </div>
</nav>
<div class="user-info container-fluid shadow-sm row mb-5">
    <div class="col-md-7 clearfix">
        <img src="{!! asset('img/usr-icon.png') !!}" alt="User Icon" class="float-left mr-3 ml-3">
        <p class="mb-1">Team Member</p>
        <p class="name mb-0">Rayno van Vuuren</p>
    </div>
    <div class="col-md-5 pl-5 row">
        <!--<div class="col-md-2"></div>-->
        <div class="d-inline-block  align-top mr-3">
            Advisor:
        </div>
        <div class="d-inline-block">
            W: 012 368 9900<br>rayno@attooh.co.za
        </div>
        <div class="col-md-6 text-right">
            <a href="" class="btn btn-primary btn-active-user px-4">Active User</a>
        </div>
    </div>
</div>

<div class="container-fluid chart-wrapper">
    <div class="rotate height-chart text-right">
        <p>Pipeline and Performance</p>
    </div>
    <div>
        <!---->
        <canvas id="pipeline-performance" width="429" height="280"></canvas>
    </div>
    <div class="align-middle">
        <div class="table-responsive">
            <table class="table table-sm text-right">
                <thead>
                <tr>
                    <th>AUM</th>
                    <th>Revenue</th>
                    <th>Revenue (Ongoing)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>R2,000,000</td>
                    <td>R10,000</td>
                    <td>R10,000</td>
                </tr>
                <tr>
                    <td>R1,500,000</td>
                    <td>R5,000</td>
                    <td>R5,000</td>
                </tr>
                <tr>
                    <td>R100,000</td>
                    <td>R350,000</td>
                    <td>R350,000</td>
                </tr>
                <tr>
                    <td>R2,000,000</td>
                    <td>R120,000</td>
                    <td>R120,000</td>
                </tr>
                <tr>
                    <td>R450,000</td>
                    <td>R80,000</td>
                    <td>R80,000</td>
                </tr>
                <tr>
                    <td>R5,000,000</td>
                    <td>R123,000</td>
                    <td>R123,000</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="container-fluid task-wrapper mt-5">
    <!--<div class="col-md-1">
      <div class="rotate">
          <p>Tasks</p>
      </div>
    </div>-->
    <!--<div class="text-center">-->
    <div class="rotate  text-center">
        <p>Tasks</p>
    </div>
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/task-bell.svg') !!}" alt="Tasks Bell">
            </div>
            <div class="col-md-6 count">
                19
            </div>
        </div>
        <p>My Overdue Tasks</p>
    </div>
    <!--</div>
    <div class="text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/task-checklist.svg') !!}" alt="Tasks Checklist">
            </div>
            <div class="col-md-6 count">
                19
            </div>
        </div>
        <p>My Tasks for Today</p>
    </div>
    <!--</div>
    <div class="text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/future-tasks.svg') !!}" alt="Future Tasks Checklist">
            </div>
            <div class="col-md-6 count">
                22
            </div>
        </div>
        <p>My Future Tasks</p>
    </div>
    <!--</div>
    <div class="text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/delegated-tasks.svg') !!}" alt="Delegated Tasks Checklist">
            </div>
            <div class="col-md-6 count">
                94
            </div>
        </div>
        <p>Delegated Tasks</p>
    </div>
    <!--</div>-->
</div>
<div class="interaction-wrapper container-fluid my-5 ">
    <!--<div class="col-md-1">-->
    <div class="rotate  text-center">
        <p>Interactions</p>
    </div>
    <!--</div>-->
    <!--<div class="col-md-2 text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/calendar-1.svg') !!}" alt="Calendar">
            </div>
            <div class="col-md-6 count">
                3
            </div>
        </div>
        <p>My Overdue Tasks</p>
    </div>
    <!--</div>-->
    <!--<div class="col-md-2 text-center">-->
    <div class="shadow text-center task">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/closing-appoitment.svg') !!}" alt="Closing Appointments">
            </div>
            <div class="col-md-6 count">
                7
            </div>
        </div>
        <p>Closing Appointments</p>
    </div>
    <!--</div>
    <div class="col-md-2 text-center">-->
    <div class="shadow text-center task">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/review-appointments.svg') !!}" alt="Review Appointments">
            </div>
            <div class="col-md-6 count">
                7
            </div>
        </div>
        <p>Review Appointments</p>
    </div>
    <!--</div>
    <div class="col-md-2 text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/fyc-icon.svg') !!}" alt="Average FYC Size Accepted">
            </div>
            <div class="col-md-6 count">
                R4,000
            </div>
        </div>
        <p>Average FYC Size Accepted</p>
    </div>
    <!--</div>
    <div class="col-md-2 text-center">-->
    <div class="shadow task text-center">
        <div class="row clearfix mb-2">
            <div class="col-md-6">
                <img src="{!! asset('img/aum-icon.svg') !!}" alt="Average AUM Size Accepted">
            </div>
            <div class="col-md-6 count">
                R600K
            </div>
        </div>
        <p>Average AUM Size Accepted</p>
    </div>
    <!--</div>-->
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="https://use.fontawesome.com/73d3fb943c.js"></script>
<script src="{!! asset('js/main.js') !!}" type="text/javascript"></script>
</body>
</html>
