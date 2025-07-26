<header>
    <nav class="main-nav navbar navbar-expand-lg shadow-sm py-0 fixed-top">
        <div class="container">
            <!-- Logo -->
            <div class="navbarBrand">
                <a href="{{ route('notas.index') }}" class="navbar-brand d-flex align-items-center py-2">
                    <img src="{{ asset('img/borboleta.png') }}" alt="Logo" class="logo-icon img-fluid w-5 me-3">
                    <span class="logo-text me-5">Bloco de Notas</span>
                </a>
            </div>


            <!-- Botão hamburguer -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarConteudo" aria-controls="navbarConteudo" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Conteúdo colapsável -->
            <div class="collapse navbar-collapse" id="navbarConteudo">
                <!-- Links de navegação -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-3">
                    <li class="nav-item">
                        <a href="{{ route('notas.index') }}" class="nav-link {{ request()->routeIs('notas.*') ? 'active' : '' }}">Minhas Notas</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tags.index') }}" class="nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">Tags</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Estatísticas</a>
                    </li>
                </ul>

                <!-- Área do usuário -->

                <div class="user-info d-flex align-items-center gap-2">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary user-link">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn user-link logout">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
