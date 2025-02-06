<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark" style="  background-color:rgb(0, 92, 179) !important; color: white !important"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="{{route('home')}}" class="brand-link">
            <!--begin::Brand Image--> <img src="{{asset('iconos/logo-empresa.png')}}" alt="SIAV"
                class="brand-image opacity-75 shadow" width="300px"> <!--end::Brand Image--> <!--begin::Brand Text-->
            <span class="brand-text fw-light"><strong></strong></span> <!--end::Brand Text--> </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->

            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                @if(Auth::user()->hasRole('superAdmin') || Auth::user()->hasRole('empleado'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa-brands fa-uncharted"></i>
                            <p>
                                Gestionar Sistema
                                <i class="nav-arrow fas fa-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('categorias.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-list-alt"></i>
                                    <p>Categorías</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('subcategorias.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-list-ol"></i>
                                    <p>Subcategorías</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('tasas.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-coins"></i>
                                    <p>Moneda</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('almacen') }}" class="nav-link">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>Productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                              <a href="{{route('proveedores.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Proveedores</p>
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a href="{{route('usuarios.clientes')}}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Clientes</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                Ventas
                                <i class="nav-arrow fas fa-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('ventas.vender')}}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>Vender</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('ventas.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>Historial</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                   <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>
                                Compras
                                <i class="nav-arrow fas fa-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('compras.comprar')}}" class="nav-link">
                                    <i class="nav-icon fas fa-cart-plus"></i>
                                    <p>Comprar</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('compras.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-receipt"></i>
                                    <p>Historial</p>
                                </a>
                            </li>
                        </ul>
                    </li> 

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>
                                Finanzas
                                <i class="nav-arrow fas fa-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('aperturas.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-cash-register"></i>
                                    <p>Caja</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('pagos.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-hand-holding-usd"></i>
                                    <p>Pagos</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if(Auth::user()->hasRole('superAdmin'))
                    <li class="nav-item">
                        <a href="{{route('usuarios.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>
                                Administración
                                <i class="nav-arrow fas fa-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('ventas.reporte')}}" class="nav-link">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Ventas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('compras.reporte')}}" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>Compras</p>
                                </a>
                            </li>
                            
                            
                            <li class="nav-item">
                                <a href="{{route('cierres_caja.reporte')}}" class="nav-link">
                                    <i class="nav-icon fas fa-door-closed"></i>
                                    <p>Cierres</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                @else(Auth::role('cliente'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>{{Auth::user()->name ?? ''}}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('ventas.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <p>Mis compras</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('carrito.show')}}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Carrito</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('products')}}" class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>Seguir comprando</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('pagos.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>Gestión de Pagos</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('usuarios.index')}}" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Perfil</p>
                        </a>
                    </li>

                @endif


            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->