<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=0" class="nav-item">
                        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Dashboard</a>
                    </li>
                    <li class="nav-title">
                        Mantenimiento
                    </li>
                    @if (Auth::user()->puede('VER_PRODUCTOS') || Auth::user()->puede('VER_CATEGORIAS'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Almacén</a>
                        <ul class="nav-dropdown-items">
                            @if (Auth::user()->puede('VER_CATEGORIAS'))
                            <li @click="menu=1" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-bag"></i> Categorías</a>
                            </li>
                            @endif
                            @if (Auth::user()->puede('VER_PRODUCTOS'))
                            <li @click="menu=2" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-bag"></i> Productos</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->puede('VER_INGRESOS') || Auth::user()->puede('VER_PROVEEDORES'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Compras</a>
                        <ul class="nav-dropdown-items">
                            @if (Auth::user()->puede('VER_INGRESOS'))
                            <li @click="menu=3" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-wallet"></i> Ingresos</a>
                            </li>
                            @endif
                            @if (Auth::user()->puede('VER_PROVEEDORES'))
                            <li @click="menu=4" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-notebook"></i> Proveedores</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->puede('VER_VENTAS') || Auth::user()->puede('VER_CLIENTES'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-basket"></i> Ventas</a>
                        <ul class="nav-dropdown-items">
                            @if (Auth::user()->puede('VER_VENTAS'))
                            <li @click="menu=5" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-basket-loaded"></i> Ventas</a>
                            </li>
                            @endif
                            @if (Auth::user()->puede('VER_CLIENTES'))
                            <li @click="menu=6" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-notebook"></i> Clientes</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->puede('VER_USUARIOS') || Auth::user()->puede('VER_ROLES'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-people"></i> Acceso</a>
                        <ul class="nav-dropdown-items">
                            @if (Auth::user()->puede('VER_USUARIOS'))
                            <li @click="menu=7" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-user"></i> Usuarios</a>
                            </li>
                            @endif
                            @if (Auth::user()->puede('VER_ROLES'))
                            <li @click="menu=8" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-user-following"></i> Roles</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->puede('VER_INGRESOS') || Auth::user()->puede('VER_VENTAS'))
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-pie-chart"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            @if (Auth::user()->puede('VER_INGRESOS'))
                            <li @click="menu=9" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ingresos</a>
                            </li>
                            @endif
                            @if (Auth::user()->puede('VER_VENTAS'))
                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-chart"></i> Reporte Ventas</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @if (Auth::user()->puede('VER_EMPRESAS'))
                    <li @click="menu=13" class="nav-item">
                        <a class="nav-link" href="#"><i class="icon-home"></i> Empresa</a>
                    </li>
                    @endif
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>