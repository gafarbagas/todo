@auth
    <nav class="navbar bg-light">
        <div class="container">
            <a class="navbar-brand">TODO AQ</a>

            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Hi, {{ auth()->user()->name }}!
                </a>
                <ul class="dropdown-menu">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <li><button class="dropdown-item" href="#">Logout</button></li>
                </form>
                </ul>
            </div>
        </div>
    </nav>
@endauth
