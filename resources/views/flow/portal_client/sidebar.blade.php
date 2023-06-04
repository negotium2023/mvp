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
                <img src="{{route('portal.client.getavatar',['q'=>(null !== Auth::guard('clients')->check()) ? Auth::guard('clients')->user()->avatar : ''])}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="" class="d-block">{{ Auth::guard('clients')->check() ? Auth::guard('clients')->user()->client->first_name.' '.Auth::guard('clients')->user()->client->last_name : '' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2" style="padding-bottom: 150px;">
            @if(Auth::guard('clients')->check())
                <ul class="nav nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->

                    <li class="nav-item nav-treeview">
                        <a href="{{route('portal.client')}}" class="nav-link {{ (\Request::route()->getName() == 'portal.client') ? 'active' : '' }}">
                            <i class="nav-icon far fa-address-card"></i>
                            <p>
                                Client Details
                            </p>
                        </a>
                    </li>
                    
                    <li class="nav-item has-treeview">
                        <a href="{{route('portal.client.documents')}}" class="nav-link {{ (\Request::route()->getName() == 'portal.client.documents') ? 'active' : '' }}">
                            <i class="nav-icon far fa-file-alt"></i>
                            <p>
                                Documents
                            </p>
                        </a>
                    </li>
                    
                </ul>
            @endif
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
