<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <ul class="navbar-nav">


            <li class="nav-item">
                <a class="nav-link" href="./">Home</a>
            </li>


            @can("show_users")
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users_index') }}">Users</a>
            </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link" href="{{ route('products_list') }}">Products</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('brought_products') }}">Bought Products</a>
            </li>
            @can('stock_operations')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stock_operations') }}">Stock Operations</a>
            </li>
            @endcan



        </ul>

        
        <ul class="navbar-nav">

            @auth
                <li class="nav-item"><a class="nav-link" href="{{route('profile')}}">{{auth()->user()->name}}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('do_logout')}}">Logout</a></li>
            @else
                <li class="nav-item"><a class="nav-link" href="{{route('login')}}">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('register')}}">Register</a></li>
            @endauth

        </ul>
        
    </div>
</nav>