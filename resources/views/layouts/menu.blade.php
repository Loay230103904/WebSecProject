<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white" href="{{ route('products_list') }}">
            <i class="fas fa-store me-2"></i>Product Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products_list') }}">
                        <i class="fas fa-box me-1"></i>Products
                    </a>
                </li>
                @auth
                    @if(auth()->user()->hasRole('Customer'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products_purchased') }}">
                            <i class="fas fa-shopping-bag me-1"></i>My Purchases
                        </a>
                    </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-item-text">
                                <small class="text-muted">Credit Balance</small>
                                <div class="fw-bold">${{ number_format(auth()->user()->credit, 2) }}</div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('do_logout') }}" method="POST" class="dropdown-item p-0">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
