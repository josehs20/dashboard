<style>
    .iconNavBar {
        font-size: 22px !important;
        float: left;
    }

    .vertical {
        transform: rotate(180deg) !important;
        transform: rotateY(180deg) !important;
        float: left;
        margin-bottom: 10px;
    }
</style>
<!-- Sidebar -->

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    @if (auth()->user() &&
        auth()->user()->administrador())

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('empresas.index') }}">
            <div class="sidebar-brand-icon">
                <img width="50px;" src="{{ asset('img/logo2.png') }}" alt="">
            </div>
            <div class="sidebar-brand-text mx-3">All Tech</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="ri-community-line" style="font-size: 25px !important;"></i>
                <span class="mx-2">Administrador</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Menu Administrativo</h6>
                    <a class="collapse-item" href="{{ route('empresas.index') }}">Empresas</a>
                    <a class="collapse-item" href="{{ route('empresas.create') }}">Registrar empresa</a>

                </div>
            </div>
        </li>

        <li class="nav-item mb-3">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePagesConta"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400" style="font-size: 20px !important;"></i>

                <span class="">Configurações</span>
            </a>
            <div id="collapsePagesConta" class="collapse" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white collapse-inner rounded">
                    <h6 class="collapse-header">Configurações de conta</h6>
                    <a class="collapse-item" href="{{ route('profile_admin') }}">Editar Perfil</a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </div>
        </li>
    @else
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard.index') }}">
            <div class="sidebar-brand-icon">
                <img width="50px;" src="{{ asset('img/logo2.png') }}" alt="">
            </div>
            <div class="sidebar-brand-text mx-3">All Tech</div>
        </a>

        <!-- Divider -->
        @if (auth()->user() &&
            auth()->user()->adminVenda())
            <hr class="sidebar-divider">
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item mb-3 {{ $activePage == 'vendedores' ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="ri-shield-user-line" style="font-size: 25px !important;"></i>
                    <span class="">Vendedores</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white collapse-inner rounded">
                        <h6 class="collapse-header">Admin vendedores</h6>
                        <a class="collapse-item"
                            href="{{ route('admin.vendedores.index', auth()->user()->id) }}">VE-Vendedores</a>

                    </div>
                </div>
            </li>
        @endif

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ $activePage == 'dasboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - Tables -->

        <li class="nav-item {{ $activePage == 'caixa' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('caixa.index') }}">
                <i class="fas fa-fw fa-table iconNavBar"></i>
                <span>Resumo de caixa</span></a>
        </li>
        <li class="nav-item {{ $activePage == 'vendas' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('vendas.index') }}">
                <i class="ri-money-dollar-box-line iconNavBar"></i>
                <span>Resumo de vendas</span></a>
        </li>
        <li class="nav-item {{ $activePage == 'maisVendidos' ? 'active' : '' }}">
            <a class="nav-link"
                href="{{ route('produtos_mais_vendidos') }}">
                <i class="ri-product-hunt-line iconNavBar"></i>
                <span>Produtos mais vendidos</span></a>
        </li>
        <li class="nav-item {{ $activePage == 'receber' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('receitas.index') }}">
                <div class="vertical"><i class="ri-hand-coin-line iconNavBar"></i></div>
                <span>Contas a receber</span>
            </a>
        </li>
        <li class="nav-item {{ $activePage == 'despesas' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('despesas.index') }}">
                <i class="ri-hand-coin-line iconNavBar"></i>
                <span>Contas a pagar</span></a>
        </li>
        <li class="nav-item {{ $activePage == 'estoques' ? 'active' : '' }}">
            <a class="nav-link"
                href="{{ route('produtos.index') }}">
                <i class="ri-indent-increase iconNavBar"></i>
                <span>Consulta de estoque</span></a>
        </li>


        <hr class="sidebar-divider my-0">
        {{-- Profiles --}}
        <li class="nav-item mb-3 {{ $activePage == 'configuracoes' ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePagesConta"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-cogs fa-sm fa-fw" style="font-size: 20px !important;"></i>

                <span class="">Configurações</span>
            </a>
            <div id="collapsePagesConta" class="collapse" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white collapse-inner rounded">
                    <h6 class="collapse-header">Configurações de conta</h6>
                    <a class="collapse-item" href="{{ route('profile_adminVenda') }}">Editar Perfil</a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal"
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </div>
        </li>

    @endif
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- fim Sidebar -->
