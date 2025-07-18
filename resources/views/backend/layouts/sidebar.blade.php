<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('backend/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('backend/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">  <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
           </li>

           @if (auth()->user()->role=='Admin')
             
            <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
                {{-- <span class="badge badge-info right">6</span> --}}
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/all-products')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Product</p>
                </a>
              </li>    

              <li class="nav-item">
                <a href="{{ URL::to('/add-product')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Product</p>
                </a>
              </li>   
              
                <li class="nav-item">
                <a href="{{ URL::to('sales/create')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales & Invoice</p>
                </a>
              </li>     
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Reports
                  <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                 <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('/monthly-sales-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly sales report</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{ URL::to('/report/daily-sales')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daily sales report</p>
                </a>
              </li>
            </ul>
              </li>  
               <li class="nav-item">
                <a href="{{ URL::to('/stock-report')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Report</p>
                </a>
              </li>  
               <li class="nav-item">
                <a href="{{ URL::to('/reports/profit')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Profit Report</p>
                </a>
              </li>  
      
          </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ URL::to('dashboard/stats')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ChartJS</p>
                </a>
              </li>
            </ul>
          </li>
                  
          @endif
          {{-- --LOGOUT-- --}}
          <li class="nav-item">
            <a class="dropdown-item" href="{{route('logout')}}"
               onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
              {{ __('Logout')}}
            </a>

            <form id="logout-form" action="{{ route('logout')}}" method="POST" class="nav-link">
                 @csrf
            </form>
          </li> 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
