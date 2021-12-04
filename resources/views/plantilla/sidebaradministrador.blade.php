<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=0" class="nav-item">
                        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                    </li>
                    <li class="nav-title">
                        Mantenimiento
                    </li>
                    <li @click="menu=2" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-user" aria-hidden="true"></i> <strong>Perfil</strong></a>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-archive" aria-hidden="true"></i> Inventario</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=1" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-tasks" aria-hidden="true"></i> Categorías</a>
                            </li>
                            <li @click="menu=3" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-book" aria-hidden="true"></i> Productos</a>
                            </li>
                            <li @click="menu=4" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-book" aria-hidden="true"></i> Proveedores</a>
                            </li>
                            <li @click="menu=5" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-card" aria-hidden="true"></i> Clientes</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Compras</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=6" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Ingresos</a>
                            </li>
                            <li @click="menu=7" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Materia Prima</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=8" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i> Registar Venta</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=9" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-user" aria-hidden="true"></i> Roles</a>
                            </li>
                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-users" aria-hidden="true"></i> Usuarios</a>
                            </li>
                            <li @click="menu=11" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-book-o" aria-hidden="true"></i> Personas</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-pie-chart" aria-hidden="true"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=12" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ingresos</a>
                            </li>
                            <li @click="menu=13" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i> Reporte ventas</a>
                            </li>
                            <li @click="menu=14" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-tasks" aria-hidden="true"></i> Reporte Categorias</a>
                            </li>
                            <li @click="menu=15" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-book" aria-hidden="true"></i> Reporte Productos</a>
                            </li>
                            <li @click="menu=16" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-book" aria-hidden="true"></i> Reporte Proveedores</a>
                            </li>
                            <li @click="menu=17" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Reporte Materias</a>
                            </li>
                            <li @click="menu=18" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-book-o" aria-hidden="true"></i> Reporte Personas</a>
                            </li>
                            <li @click="menu=19" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-card" aria-hidden="true"></i> Reporte Clientes</a>
                            </li>
                        </ul>
                    </li>
                    <!--
                    <li class="nav-item">                            
                        <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            <i class="nav-icon fa fa-power-off "></i>
                               
                                {{ __('Logout') }}
                            
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>-->
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>