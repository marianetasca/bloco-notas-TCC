<header>
    <nav class="main-nav">
        <div class="nav-container">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('notas.index') }}">
                    <img src="{{ asset('img/borboleta.png') }}" alt="Logo" class="logo-icon">
                    <p class="logo-text">Bloco de Notas</p>
                </a>
            </div>

            <!-- Navigation Links -->
            <ul class="nav-links">
                <li><a href="{{ route('notas.index') }}"
                        class="{{ request()->routeIs('notas.*') ? 'active' : '' }}">Minhas Notas</a></li>
                <li><a href="{{ route('categorias.index') }}"
                        class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorias</a></li>
                <li><a href="{{ route('tags.index') }}"
                        class="{{ request()->routeIs('tags.*') ? 'active' : '' }}">Tags</a></li>
                <li><a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Estatísticas</a></li>
            </ul>

            <!-- Área do usuário -->
            <div class="user-info">
                <span class="user-name"> {{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="user-link">Perfil</a>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="user-link logout">Sair</button>
                </form>
            </div>
        </div> <!-- fecha nav-container -->
    </nav>

    <!-- Page Header (opcional) -->
    {{-- @if (isset($header))
        <div class="page-header">
            <div class="header-inner">
                {{ $header }}
            </div>
        </div>
    @endif --}}
</header>
