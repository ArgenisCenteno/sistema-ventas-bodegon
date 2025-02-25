<style>
    /* Asegurar que los enlaces sean blancos */
    .navbar-nav .nav-link {
        color: white !important;
    }

    /* Estilos para que los dropdowns se abran al pasar el mouse */
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
    }

    /* Ajustar color de los elementos dentro del dropdown */
    .dropdown-menu .dropdown-item {
        color: black !important;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #007bff;
        color: white !important;
    }
</style>

<nav class="app-header navbar bg-primary text-white navbar-expand bg-body p-3" style="background-color: rgb(0, 92, 179) !important; color: white !important"> 
    <div class="container-fluid"> 
        <ul class="navbar-nav">
            <li class="nav-item"> 
                <a class="nav-link text-white" href="{{ url('/home') }}"> 
                    EL BODEGÓN DE PELUCHE
                </a> 
            </li>
        </ul> 
        <ul class="navbar-nav mx-auto"> 
            @if(Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('empleado'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="gestionDropdown" role="button">
                        <i class="bi bi-gear"></i> Gestionar Sistema
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="gestionDropdown">
                        <li><a class="dropdown-item" href="{{ route('categorias.index') }}"><i class="bi bi-tags"></i> Categorías</a></li>
                        <li><a class="dropdown-item" href="{{ route('subcategorias.index') }}"><i class="bi bi-tag"></i> Subcategorías</a></li>
                        <li><a class="dropdown-item" href="{{ route('tasas.index') }}"><i class="bi bi-currency-exchange"></i> Moneda</a></li>
                        <li><a class="dropdown-item" href="{{ route('almacen') }}"><i class="bi bi-box"></i> Productos</a></li>
                        <li><a class="dropdown-item" href="{{ route('proveedores.index') }}"><i class="bi bi-truck"></i> Proveedores</a></li>
                        <li><a class="dropdown-item" href="{{ route('usuarios.clientes') }}"><i class="bi bi-people"></i> Clientes</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="ventasDropdown" role="button">
                        <i class="bi bi-cart"></i> Ventas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="ventasDropdown">
                        <li><a class="dropdown-item" href="{{ route('ventas.vender') }}"><i class="bi bi-cart-plus"></i> Vender</a></li>
                        <li><a class="dropdown-item" href="{{ route('ventas.index') }}"><i class="bi bi-clock-history"></i> Historial</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="comprasDropdown" role="button">
                        <i class="bi bi-basket"></i> Compras
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="comprasDropdown">
                        <li><a class="dropdown-item" href="{{ route('compras.comprar') }}"><i class="bi bi-cart-check"></i> Comprar</a></li>
                        <li><a class="dropdown-item" href="{{ route('compras.index') }}"><i class="bi bi-clipboard-check"></i> Historial</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="finanzasDropdown" role="button">
                        <i class="bi bi-cash-stack"></i> Finanzas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="finanzasDropdown">
                        <li><a class="dropdown-item" href="{{ route('aperturas.index') }}"><i class="bi bi-wallet"></i> Caja</a></li>
                        <li><a class="dropdown-item" href="{{ route('pagos.index') }}"><i class="bi bi-credit-card"></i> Pagos</a></li>
                    </ul>
                </li>
                @if(Auth::user()->hasRole('superAdmin'))
                    <li class="nav-item"><a class="nav-link text-white" href="{{ route('usuarios.index') }}"><i class="bi bi-person-circle"></i> Usuarios</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="adminDropdown" role="button">
                            <i class="bi bi-sliders"></i> Administración
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="{{ route('ventas.reporte') }}"><i class="bi bi-graph-up"></i> Ventas</a></li>
                            <li><a class="dropdown-item" href="{{ route('compras.reporte') }}"><i class="bi bi-bar-chart"></i> Compras</a></li>
                            <li><a class="dropdown-item" href="{{ route('cierres_caja.reporte') }}"><i class="bi bi-clipboard-data"></i> Cierres</a></li>
                        </ul>
                    </li>
                @endif
            @else
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('ventas.index') }}"><i class="bi bi-bag"></i> Mis compras</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('carrito.show') }}"><i class="bi bi-cart-fill"></i> Carrito</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('products') }}"><i class="bi bi-shop"></i> Seguir comprando</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('pagos.index') }}"><i class="bi bi-wallet2"></i> Gestión de Pagos</a></li>
            @endif
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-white" href="#" id="perfilDropdown" role="button">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="perfilDropdown">
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div> 
</nav>
