<nav class="app-header navbar bg-light navbar-expand bg-body"> 
    <!--begin::Container-->
    <div class="container-fluid"> 
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item"> 
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"> 
                    <i class="bi bi-list"></i> 
                </a> 
            </li>
        </ul> 
        <!--end::Start Navbar Links--> 
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto"> 
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu"> 
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> 
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span> 
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> 
                    <!--begin::User Info-->
                    <li class="user-header text-bg-info text-center py-3">
                        <p class="mb-1 fw-bold">{{ Auth::user()->name }}</p>
                        <p class="mb-0">
                            <i class="material-icons">verified_user</i>
                            @if(Auth::user()->hasRole('superAdmin'))
                                Super Admin
                            @else
                                Empleado
                            @endif
                        </p>
                    </li> 
                    <!--end::User Info--> 
                    <!--begin::Menu Body-->
                    <li>
                        <a class="dropdown-item" href="{{ route('usuarios.edit', Auth::id()) }}">
                            <i class="material-icons">person</i> Perfil
                        </a>
                    </li>
                    <li class="user-footer"> 
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="material-icons">logout</i> Salir
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li> 
                    <!--end::Menu Footer-->
                </ul>
            </li> 
            <!--end::User Menu Dropdown-->
        </ul> 
        <!--end::End Navbar Links-->
    </div> 
    <!--end::Container-->
</nav>
