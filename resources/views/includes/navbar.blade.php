<nav
      class="
        navbar navbar-expand-lg navbar-light navbar-store
        fixed-top
        navbar-fixed-top
      "
      data-aos="fade-down"
    >
      <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">
          <img src="/images/logo1.svg" alt="logo" width="100" height="100" />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarResponsive"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item {{ (request()->is('/')) ? 'active' : '' }}">
              <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item {{ (request()->is('/categories*')) ? 'active' : '' }}"">
              <a href="{{ route('categories') }}" class="nav-link">Catagories</a>
            </li>
            <li class="nav-item {{ (request()->is('/contact*')) ? 'active' : '' }}"">
              <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </li>
            @guest
              <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link">Sign Up</a>
              </li>
              <li class="nav-item">
                <a
                  href="{{ route('login') }}"
                  class="btn btn-success nav-link px-4 text-white"
                  >Sign In</a
                >
              </li>
            @endguest
          </ul>

          @auth
            <ul class="navbar-nav d-none d-lg-flex" >
              <li class="navbar-item dropdown">
                <a
                  href="#"
                  class="nav-link"
                  id="navbarDropdown"
                  role="button"
                  data-toggle="dropdown"
                >
                  <img
                    src="/images/profil.png"
                    alt=""
                    class="rounded-circle mr-2 profile-picture"
                  />
                  Hi, {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu">
                  @if (Auth::user()->roles =='USER')
                  <a href="{{ route('dashboard') }}" class="dropdown-item"
                  >dashboard</a
                >
                 @else
                 <a href="{{ route('admin-dashboard') }}" class="dropdown-item"
                  >dashboard</a
                >
                  @endif
                  
                  <a href="{{ route('dashboard-setting-account') }}" class="dropdown-item"
                    >settings</a
                  >
                  <div class="dropdown-divider"></div>
                  <a href="{{ route('logout') }}" 
                      onclick="event.preventDefault(); 
                      document.getElementById('logout-form').submit();"
                      class="dropdown-item"
                    >Log out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
                      @csrf
                    </form>
                </div>
              </li>
              <li class="nav-item">
                <a href="{{ route('cart') }}" class="nav-link d-inline-block mt-2">
                  @php
                    $carts = \App\Models\Cart::where('user_id', Auth::user()->id)->count();
                  @endphp
                  @if ($carts > 0)
                    <img src="/images/icon-cart-filled.svg" alt="" />
                    <div class="card-badge">{{ $carts }}</div>
                  @endif
                </a>
              </li>
            </ul>
            <ul class="navbar-nav d-block d-lg-none">
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link">Hi, {{ Auth::user()->name }} !!!</a>
              </li>
              <li class="nav-item">
                <a href="{{ route('cart') }}" class="nav-link">Cart</a>
              </li>
            </ul>
          @endauth
        </div>
      </div>
    </nav>