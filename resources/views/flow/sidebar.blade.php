<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <span class="logo-mini"><img src="{!! asset('assets/my-virtual-practice.png') !!}" alt="Blackboard Logo"></span>
        <span class="logo-lg"><img src="{!! asset('assets/my-virtual-practice.png') !!}" alt="Blackboard Logo"></span>
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

                    @permission('maintain_client')
                    <li class="nav-item nav-treeview">
                        <a href="{{route('clients.index')}}" class="nav-link {{ (\Request::route()->getName() == 'clients.index') ? 'active' : '' }}">
                            <i class="nav-icon far fa-address-card"></i>
                            <p>
                                Clients
                            </p>
                        </a>
                    </li>
                    @endpermission
                    <li class="nav-item has-treeview">
                        <a href="{{route('clients.create')}}" class="nav-link {{ (\Request::route()->getName() == 'clients.create') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-plus"></i>
                            <p>
                                Capture Client
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="{{route('recents')}}" class="nav-link {{ (\Request::route()->getName() == 'recents') ? 'active' : '' }}">
                            <i class="nav-icon far fa-clock"></i>
                            <p>
                                Recents
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{route('workflows')}}" class="nav-link {{ (\Request::route()->getName() == 'workflows') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-trello"></i>
                            <p>
                                Pipeline
                            </p>
                        </a>
                    </li>

                    {{--<li class="nav-item has-treeview">
                        <a href="{{route('reports.task')}}" class="nav-link {{ (\Request::route()->getName() == 'reports.task') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                Task Report
                            </p>
                        </a>
                    </li>--}}

                    <li class="nav-item has-treeview">
                        <a href="{{route('reports.myworkday')}}" class="nav-link {{ (\Request::route()->getName() == 'reports.myworkday') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                My Work Day
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{route('maillog.index')}}" class="nav-link {{ (\Request::route()->getName() == 'maillog.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mail-bulk"></i>
                            <p>
                                Email History
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{route('calendar.index')}}" class="nav-link {{ (\Request::route()->getName() == 'calendarevents.index') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                Calendar
                            </p>
                        </a>
                    </li>

                    {{--<li class="nav-item has-treeview">
                        <a href="{{route('azure_task.index')}}" class="nav-link {{ (\Request::route()->getName() == 'calendarevents.index') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-book"></i>
                            <p>
                                Microsoft Task
                            </p>
                        </a>
                    </li>--}}

                    @permission('admin')
                    <li class="header"><a href="javascript:void(0)" id="admin-menu"><i class="nav-icon fa fa-briefcase"></i><p>ADMIN</p></a></li>
                    @permission('process_editor')
                    @role('manager')
                    <li class="nav-item has-treeview admin-menu">
                        <a href="{{route('processesgroup.index')}}" class="nav-link {{ Request::get('t') && (\Request::route()->getName() == 'processesgroup.index') ? '' : ((\Request::route()->getName() == 'processesgroup.index') ? 'active' : '') }}">
                            <i class="nav-icon fa fa-tasks"></i>
                            <p>
                                Processes
                            </p>
                        </a>
                    </li>
                    @endrole
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
                    @role('manager')
                    <li class="nav-item has-treeview admin-menu">
                        <a href="{{route('card.list')}}" class="nav-link {{ (\Request::route()->getName() == 'card.list') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>
                                Card
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
                        <a href="javascript:void(0)" class="nav-link {{ (\Request::route()->getName() == 'templates.index') ? 'active' : '' }}">
                            <i class="nav-icon far fa-file"></i>
                            <p>
                                Templates
                            </p>
                        </a>
                        <ul class="nav nav-treeview admin-menu">
                            <li class="nav-item has-treeview">
                                <a href="{{route('templates.index')}}" class="nav-link {{ (\Request::route()->getName() == 'templates.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle"></i>
                                    <p>
                                        Document Templates
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{route('emailtemplates.index')}}" class="nav-link {{ (\Request::route()->getName() == 'emailtemplates.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle"></i>
                                    <p>
                                        Email Templates
                                    </p>
                                </a>
                            </li>
                            {{--<li class="nav-item has-treeview">
                                <a href="{{route('whatsapptemplates.index')}}" class="nav-link {{ (\Request::route()->getName() == 'whatsapptemplates.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle"></i>
                                    <p>
                                        Whatsapp Templates
                                    </p>
                                </a>
                            </li>--}}
                        </ul>
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
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fa fa-globe"></i>
                            <p>
                                Master Data
                            </p>
                        </a>
                        <ul class="nav nav-treeview admin-menu">
                            <li class="nav-item has-treeview">
                                <a href="{{route('locations.index')}}" class="nav-link {{ (\Request::route()->getName() == 'locations.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle"></i>
                                    <p>
                                        Document<br />Categories
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item has-treeview">
                                <a href="{{route('locations.index')}}" class="nav-link {{ (\Request::route()->getName() == 'locations.index') ? 'active' : '' }}">
                                    <i class="nav-icon fa fa-circle"></i>
                                    <p>
                                        Locations
                                    </p>
                                </a>
                            </li>
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
