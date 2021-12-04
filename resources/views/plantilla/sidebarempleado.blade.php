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
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=14" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-tasks" aria-hidden="true"></i> Reporte Categorias</a>
                            </li>
                            <li @click="menu=15" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-book" aria-hidden="true"></i> Reporte Productos</a>
                            </li>
                            <li @click="menu=16" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-book" aria-hidden="true"></i> Reporte Proveedores</a>
                            </li>
                            <li @click="menu=19" class="nav-item">
                                <a class="nav-link" href="#"><i class="fa fa-address-card" aria-hidden="true"></i> Reporte Clientes</a>
                            </li>
                            </li>
                        </ul>
                    </li>

                    <!--<li class="nav-item">                            
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