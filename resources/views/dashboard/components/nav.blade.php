<nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
    <div class="navbar-header d-xl-block d-none">
        <ul class="nav navbar-nav">
            <li class="nav-item"><a class="navbar-brand" href="#"></span>
                    <h2 class="brand-text mb-0">Dashboard</h2>
                </a></li>
        </ul>
    </div>
    <div class="navbar-container d-flex content">
       
        <ul class="nav navbar-nav align-items-center ms-auto">
            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                <div class="search-input">
                    <div class="search-input-icon"><i data-feather="search"></i></div>
                    <input class="form-control input" type="text" placeholder="Rechercher" tabindex="-1" data-search="search">
                    <div class="search-input-close"><i data-feather="x"></i></div>
                    <ul class="search-list search-list-main"></ul>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-user">
                <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="{{route('profile')}}"  aria-haspopup="true" >
                    <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::user()->firstname }}   {{ Auth::user()->lastname }}</span><span class="user-status">{{ Auth::user()->role->label }}</span></div><span class="avatar"><img class="round" src="{{asset('app-assets/images/avatars/user_icon.png')}}" alt="avatar" height="40" width="40"><span class="avatar-status-online"></span></span>
                </a>
            </li>
            <form action="{{route('logout')}}" method="post">
                @csrf
                <button class="btn btn-primary " type="submit">Déconnexion</button>
            </form>
        </ul>
    </div>
</nav>