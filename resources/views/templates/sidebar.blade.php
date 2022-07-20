<style>
    .iconNavBar {
        font-size: 20px !important;
    }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">



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
                <i class="ri-community-line iconNavBar"></i>
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
    @else
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center"
            href="{{ route('dashboard.index') }}">
            <div class="sidebar-brand-icon">
                <img width="50px;" src="{{ asset('img/logo2.png') }}" alt="">
            </div>
            <div class="sidebar-brand-text mx-3">All Tech</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">
        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Resumos
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        {{-- <li class="nav-item">
              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                  aria-expanded="true" aria-controls="collapseTwo">
                  <i class="fas fa-fw fa-cog"></i>
                  <span>Components</span>
              </a>
              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                  <div class="bg-white py-2 collapse-inner rounded">
                      <h6 class="collapse-header">Custom Components:</h6>
                      <a class="collapse-item" href="buttons.html">Buttons</a>
                      <a class="collapse-item" href="cards.html">Cards</a>
                  </div>
              </div>
          </li>

          <!-- Nav Item - Utilities Collapse Menu -->
          <li class="nav-item">
              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                  aria-expanded="true" aria-controls="collapseUtilities">
                  <i class="fas fa-fw fa-wrench"></i>
                  <span>Utilities</span>
              </a>
              <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                  data-parent="#accordionSidebar">
                  <div class="bg-white py-2 collapse-inner rounded">
                      <h6 class="collapse-header">Custom Utilities:</h6>
                      <a class="collapse-item" href="utilities-color.html">Colors</a>
                      <a class="collapse-item" href="utilities-border.html">Borders</a>
                      <a class="collapse-item" href="utilities-animation.html">Animations</a>
                      <a class="collapse-item" href="utilities-other.html">Other</a>
                  </div>
              </div>
          </li>

          <!-- Divider -->
          <hr class="sidebar-divider">

          <!-- Heading -->
          <div class="sidebar-heading">
              Addons
          </div>

          <!-- Nav Item - Pages Collapse Menu -->
          <li class="nav-item">
              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                  aria-expanded="true" aria-controls="collapsePages">
                  <i class="fas fa-fw fa-folder"></i>
                  <span>Pages</span>
              </a>
              <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                  <div class="bg-white py-2 collapse-inner rounded">
                      <h6 class="collapse-header">Login Screens:</h6>
                      <a class="collapse-item" href="login.html">Login</a>
                      <a class="collapse-item" href="register.html">Register</a>
                      <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                      <div class="collapse-divider"></div>
                      <h6 class="collapse-header">Other Pages:</h6>
                      <a class="collapse-item" href="404.html">404 Page</a>
                      <a class="collapse-item" href="blank.html">Blank Page</a>
                  </div>
              </div>
          </li>

          <!-- Nav Item - Charts -->
          <li class="nav-item">
              <a class="nav-link" href="charts.html">
                  <i class="fas fa-fw fa-chart-area"></i>
                  <span>Charts</span></a>
          </li> --}}

        <!-- Nav Item - Tables -->

        <li class="nav-item">
            <a class="nav-link" href="{{ route('caixa.index') }}">
                <i class="fas fa-fw fa-table iconNavBar"></i>
                <span>Resumo de caixa</span></a>

            <a class="nav-link" href="{{ route('vendas.index') }}">
                <i class="ri-money-dollar-box-line iconNavBar"></i>
                <span>Resumo de vendas</span></a>

            <a class="nav-link" href="{{ route('produtos_mais_vendidos') }}">
                <i class="ri-product-hunt-line iconNavBar"></i>
                <span>Produtos mais vendidos</span></a>

            <a class="nav-link" href="{{ route('receitas.index') }}">
                <i class="ri-user-received-2-line iconNavBar"></i>
                <span>Contas a receber</span></a>

            <a class="nav-link" href="{{ route('despesas.index') }}">
                <i class="ri-hand-coin-line iconNavBar"></i>
                <span>Contas a pagar</span></a>

            <a class="nav-link" href="{{ route('produtos.index') }}">
                <i class="ri-indent-increase iconNavBar"></i>
                <span>Consulta de estoque</span></a>
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
