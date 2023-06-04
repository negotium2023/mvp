  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <span class="logo-lg"><img src="{!! asset('assets/xcell.png') !!}" alt="Blackboard Logo" style="width:100px;opacity: .8"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar blackboard-scrollbar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{route('avatar',['q'=>(null !== Auth::user()) ? Auth::user()->avatar : ''])}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="{{route('profile')}}" class="d-block">{{ Auth::check() ? Auth::user()->first_name.' '.Auth::user()->last_name : '' }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2" style="padding-bottom: 150px;">
        @if(Auth::check())
        <ul class="nav nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item has-treeview">
            <a href="{{route('recents')}}" class="nav-link {{ (\Request::route()->getName() == 'recents') ? 'active' : '' }}">
              <i class="nav-icon far fa-clock"></i>
              <p>
                Recents
              </p>
            </a>
          </li>
          {{--<li class="nav-item has-treeview">
            <a href="{{route('progress')}}" class="nav-link {{ (\Request::route()->getName() == 'progress') ? 'active' : '' }}">
              <i class="nav-icon fa fa-chart-line"></i>
              <p>
                Progress
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{route('dashboard')}}" class="nav-link {{ (\Request::route()->getName() == 'dashboard') ? 'active' : '' }}">
              <i class="nav-icon fa fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>--}}

          {{--<li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ (\Request::route()->getName() == 'reports.index') || (\Request::route()->getName() == 'custom_report.index') ? 'active' : '' }}">
              <i class="nav-icon far fa-chart-bar"></i>
              <p>
                Reports
                <i class="right fa fa-angle-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reports.assigned_actions')}}" class="nav-link {{ (\Request::route()->getName() == 'reports.assigned_actions') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Action Report
                  </p>
                </a>
              </li>--}}
              {{--<li class="nav-item">
                <a href="{{route('reports.index')}}" class="nav-link {{ (\Request::route()->getName() == 'reports.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Activity Reports
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('custom_report.index')}}" class="nav-link {{ (\Request::route()->getName() == 'custom_report.index') ? 'active' : '' }}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Custom Reports
                  </p>
                </a>
              </li>

              <li class="nav-item has-treeview">
                <a href="{{route('reports.auditreport')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'reports.auditreport')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Audit Reports

                  </p>
                </a>
              </li>
              @permission('admin')
              <li class="nav-item has-treeview">
                <a href="{{route('reports.usage')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'reports.usage')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Usage Reports

                  </p>
                </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="{{route('reports.productivity')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'reports.productivity')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Productivity Reports

                  </p>
                </a>
              </li>
              @endpermission
              <li class="nav-item has-treeview">
                <a href="{{route('report.qaChecklist')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'report.qaChecklist')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Checklist
                  </p>
                </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="{{route('qaanalystreport.index')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'qaanalystreport.index')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Analyst QA Report
                  </p>
                </a>
              </li>
              <li class="nav-item has-treeview">
                <a href="{{route('reports.sla')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'reports.sla')}}">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    SLA Report

                  </p>
                </a>
              </li>--}}
            {{--</ul>
          </li>--}}
          {{--<li class="nav-item has-treeview">
            <a href="{{route('reports.generate_report')}}" class="nav-link absa-nav-link {{ (\Request::route()->getName() == 'reports.generate_report') ? 'active' : ''}}">
              <i class="nav-icon fas fa-file-export"></i>
              <p>
                Generate Report
                <i class="right fa fa-angle-right"></i>
              </p>
            </a>
          </li>--}}
          @permission('maintain_client')
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ (\Request::route()->getName() == 'clients.index') ? 'active' : '' }}">
              <i class="nav-icon far fa-address-card"></i>
              <p>
                Clients
                <i class="right fa fa-angle-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @auth
                @if($sidebar_process_statuses)
                  <li class="nav-item">
                    <a href="{{route('clients.index')}}" class="nav-link">
                      <i class="fas fa-circle nav-icon"></i>
                      <p>All Clients</p>
                    </a>
                  </li>
                  {{--@foreach($sidebar_process_statuses as $sidebar_process)
                    <li class="nav-item has-treeview">
                      @if($sidebar_process["steps"] != null && count($sidebar_process["steps"]) > 0)
                        <a href="#" class="nav-link">
                          <i class="nav-icon fas fa-circle"></i>
                          <p>
                            {{$sidebar_process["name"]}}
                            <i class="right fa fa-angle-right"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          @auth

                            @foreach($sidebar_process["steps"] as $key => $value)
                              <li class="nav-item">
                                <a href="{{route('clients.index')}}?step={{$key}}&p={{$sidebar_process["id"]}}" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>{{$value}}</p>
                                </a>
                              </li>
                            @endforeach
                          @endauth
                        </ul>
                      @else
                        <a href="{{route('processes.show',$sidebar_process["id"])}}" class="nav-link">
                          <i class="nav-icon fas fa-circle"></i>
                          <p>
                            {{$sidebar_process["name"]}}
                          </p>
                        </a>
                      @endif
                    </li>
                  @endforeach--}}
                  {{--<li class="nav-item">
                    <a href="{{route('clients.index')}}?step=1000" class="nav-link">
                      <i class="fas fa-circle nav-icon"></i>
                      <p>Current Applications</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('clients.index')}}?step=1001" class="nav-link">
                      <i class="fas fa-circle nav-icon"></i>
                      <p>Completed Applications</p>
                    </a>
                  </li>--}}
                        {{--@role('qa')
                        <li class="nav-item">
                            <a href="{{route('clients.index')}}?step=1002" class="nav-link">
                                <i class="nav-icon fas fa-circle nav-icon"></i>
                                <p>QA</p>
                            </a>
                        </li>
                        @endrole--}}
                @endif
              @endauth
            </ul>
          </li>
          @endpermission
          <li class="nav-item has-treeview">
            <a href="{{route('clients.create')}}" class="nav-link {{ (\Request::route()->getName() == 'clients.create') ? 'active' : '' }}">
              <i class="nav-icon fa fa-plus"></i>
              <p>
                @if(auth()->user()->is('manager'))
                  Capture Client
                @else
                  Capture Client
                @endif

              </p>
            </a>
          </li>

          @permission('admin')
          <li class="header"><a href="javascript:void(0)" id="admin-menu">ADMIN</a></li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link {{ (\Request::route()->getName() == 'clients.index') ? 'active' : '' }}">
              <i class="nav-icon far fa-address-card"></i>
              <p>
                OAuth
                <i class="right fa fa-angle-right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('passport.clients')}}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Clients</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('passport.authorizedclients')}}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Authorized Clients</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('passport.personalaccesstokens')}}" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Tokens</p>
                </a>
              </li>
            </ul>
          </li>



          @permission('process_editor')
            @role('manager')
              <li class="nav-item has-treeview admin-menu">
                <a href="{{route('processesgroup.index')}}" class="nav-link {{ Request::get('t') && (\Request::route()->getName() == 'processesgroup.index') ? '' : (\Request::route()->getName() == 'processesgroup.index') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-tasks"></i>
                  <p>
                    Processes
                  </p>
                </a>
              </li>
            @endrole
          {{--<li class="nav-item has-treeview admin-menu">
            <a href="{{route('processes.index')}}?t=2" class="nav-link {{ Request::get('t') && (\Request::route()->getName() == 'processes.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-id-badge"></i>
              <p>
                Related Parties Structure
              </p>
            </a>
          </li>--}}
          @role('manager')
          <li class="nav-item has-treeview admin-menu">
            <a href="{{route('forms.index')}}" class="nav-link {{ (\Request::route()->getName() == 'forms.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-file"></i>
              <p>
                CRM
              </p>
            </a>
          </li>
          @endrole
          @endpermission
          @permission('maintain_document')
          @role('manager')
          <li class="nav-item has-treeview admin-menu">
            <a href="{{route('documents.index')}}" class="nav-link {{ (\Request::route()->getName() == 'documents.index') ? 'active' : '' }}">
              <i class="nav-icon far fa-file-alt"></i>
              <p>
                Documents
              </p>
            </a>
          </li>
          @endrole
          @endpermission
          @permission('maintain_template')
          @role('manager')
          <li class="nav-item has-treeview admin-menu">
            <a href="{{route('templates.index')}}" class="nav-link {{ (\Request::route()->getName() == 'templates.index') ? 'active' : '' }}">
              <i class="nav-icon far fa-file"></i>
              <p>
                Templates
              </p>
            </a>
          </li>
          @endrole
          @endpermission
          @permission('actions')
          @role('manager')
          <li class="nav-item has-treeview admin-menu">
            <a href="{{route('action.index')}}" class="nav-link {{ (\Request::route()->getName() == 'action.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-project-diagram"></i>
              <p>
                Actions
              </p>
            </a>
          </li>
          @endrole
          @endpermission
          @role('manager')
          <li class="nav-item has-treeview admin-menu">
            <a href="{{route('emailtemplates.index')}}" class="nav-link {{ (\Request::route()->getName() == 'emailtemplates.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Email Templates
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview admin-menu">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fa fa-globe"></i>
              <p>
                Master Data
              </p>
            </a>
            <ul class="nav nav-treeview admin-menu">
              {{--<li class="nav-item has-treeview">
                <a href="{{route('businessunits.index')}}" class="nav-link {{ (\Request::route()->getName() == 'businessunits.index') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-circle"></i>
                  <p>
                    Business Units
                  </p>
                </a>
              </li>
              <li class="nav-item has-treeview admin-menu">
                <a href="{{route('committees.index')}}" class="nav-link {{ (\Request::route()->getName() == 'committees.index') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-circle"></i>
                  <p>
                    Committees
                  </p>
                </a>
              </li>--}}
              <li class="nav-item has-treeview">
                <a href="{{route('locations.index')}}" class="nav-link {{ (\Request::route()->getName() == 'locations.index') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-circle"></i>
                  <p>
                    Locations
                  </p>
                </a>
              </li>
              {{--<li class="nav-item has-treeview">
                <a href="{{route('projects.index')}}" class="nav-link {{ (\Request::route()->getName() == 'projects.index') ? 'active' : '' }}">
                  <i class="nav-icon fa fa-circle"></i>
                  <p>
                    Projects
                  </p>
                </a>
              </li>--}}
            </ul>
          </li>
          @endrole
          <li class="nav-item has-treeview admin-menu">
            <a href="{{ route('users.index')}}" class="nav-link {{ (\Request::route()->getName() == 'users.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          @role('manager')
          <li class="nav-item admin-menu">
            <a href="{{ route('roles.index') }}" class="nav-link {{ (\Request::route()->getName() == 'roles.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-cog"></i>
              <p>
                Roles
              </p>
            </a>
          </li>
          <li class="nav-item admin-menu">
            <a href="{{route('configs.index')}}" class="nav-link {{ (\Request::route()->getName() == 'configs.index') ? 'active' : '' }}">
              <i class="nav-icon fa fa-cogs"></i>
              <p>
                Configs
              </p>
            </a>
          </li>
          @endrole
          @endpermission

          @endif
        </ul>
        <ul class="nav nav-sidebar support flex-column" role="menu" data-accordion="false" style="width:93%;position: absolute;bottom: 0;">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="https://support.blackboardbs.com/requester/tickets/create" target="_blank" class="nav-link">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                Support
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="javascript:void(0)" class="nav-link">
              <i class="nav-icon fas fa-phone"></i>
              <p>
                081 578 9429
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
