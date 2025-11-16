<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mindly Notes - Organize suas ideias com inteligência</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style> /* Optei por deixar o css aqui por ser tudo especifico somente para esta página */
        :root {
            --primary-color: #2563EB;
            --secondary-color: #7C3AED;
            --accent-color: #10B981;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: aliceblue;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .hero {
            background: linear-gradient(135deg, #64a1dd 0%, #5e14ff 100%);
            color: white;
            padding: 100px 0 80px;
            min-height: 100vh;
        }

        .border-end { /* Separador vertical entre links */
            height: 20px;
            opacity: 1;
        }

        .user-login {
            border: none;
            color: white;
            background: linear-gradient(135deg, #0062ad 0%, #004080 100%);
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 98, 173, 0.25);
        }

        .user-login:hover {
            color: white;
            background: linear-gradient(135deg, #004080 0%, #002952 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 98, 173, 0.4);
        }

        .user-login::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(255, 255, 255, 0.3),
                    transparent);
            transition: left 0.6s ease;
        }

        .user-login:hover::before {
            left: 100%;
        }

        .user-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 98, 173, 0.3);
        }

        .feature-section {
            padding: 80px 0;
        }

        .feature-section:nth-child(even) {
            background-color: #F9FAFB;
        }
        /* Fim Navbar */

        .screenshot-placeholder {
            background: linear-gradient(135deg, #E5E7EB 0%, #D1D5DB 100%);
            border-radius: 12px;
            min-width: 370px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: groove rgba(0, 25, 79, 0.1);
        }

        .screenshot-placeholder img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            text-align: center;
            padding: 30px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        footer {
            background: #1F2937;
            color: white;
            padding: 40px 0;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: #6B7280;
            margin-bottom: 3rem;
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ asset('img/borboleta.png') }}" alt="Logo" class="logo-icon img-fluid "></i> Mindly Notes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item align-content-center">
                        <a class="nav-link" href="#funcionalidades">Funcionalidades</a>
                    </li>
                    <li class="nav-item align-content-center me-2">
                        <a class="nav-link" href="#como-funciona">Como Funciona</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="btn btn-primary ms-3" href="{{ route('notas.index') }}">
                                Minhas Notas
                            </a>
                        </li>
                    @else
                        <div class="user-info d-flex align-items-center gap-2">
                            <span class="">
                                <a class=" login btn btn-primary user-login" href="{{ route('login') }}">Entrar</a>
                            </span>
                            <p class="border-end p-0 m-0"></p>
                            <span class="login">
                                <a class="btn btn-primary user-login" href="{{ route('register') }}">
                                    Registrar-se
                                </a>
                            </span>
                        </div>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-start mb-5 mb-lg-0">
                    <h1 class="display-3 fw-bold mb-4">
                        Organize suas ideias com <span class="text-warning">inteligência</span>
                    </h1>
                    <p class="lead mb-4">
                        Crie, organize e gerencie suas notas com um sistema completo de categorias,
                        tags, notificações automáticas e muito mais.
                    </p>
                    <div class="d-grid gap-3 d-sm-flex">
                        @auth
                            <a href="{{ route('notas.index') }}" class="btn btn-light btn-hero">
                                <i class="bi bi-journal-text"></i> Ir para Minhas Notas
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-light btn-hero">
                                Começar Agora
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-hero">
                                Já tenho conta
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/telaInicial.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-3 stat-card">
                    <div class="stat-number"><i class="bi bi-pencil-square text-secondary"></i></div>
                    <h5>Editor Rico</h5>
                    <p class="text-muted">Formatação completa</p>
                </div>
                <div class="col-md-3 stat-card">
                    <div class="stat-number"><i class="bi bi-bell text-warning"></i></div>
                    <h5>Notificações</h5>
                    <p class="text-muted">Lembretes automáticos</p>
                </div>
                <div class="col-md-3 stat-card">
                    <div class="stat-number"><i class="bi bi-tags text-danger"></i></div>
                    <h5>Organização</h5>
                    <p class="text-muted">Categorias e tags</p>
                </div>
                <div class="col-md-3 stat-card">
                    <div class="stat-number"><i class="bi bi-shield-check text-primary"></i></div>
                    <h5>Segurança</h5>
                    <p class="text-muted">Dados protegidos</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Funcionalidades Section --}}
    <section id="funcionalidades" class="feature-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Funcionalidades Principais</h2>
                <p class="section-subtitle">Tudo que você precisa para organizar suas ideias</p>
            </div>

            {{-- Feature 1: Editor --}}
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Editor de Texto Completo</h3>
                    <p class="lead text-muted mb-4">
                        Crie notas com formatação rica: negrito, itálico, listas, títulos e muito mais.
                        Interface intuitiva e fácil de usar.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Formatação de texto
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Listas e marcadores
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Títulos e subtítulos</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Salvamento automático</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/conteudo.png') }}" alt="formatação">
                    </div>
                </div>
            </div>

            {{-- Feature 2: Notificações --}}
            <div class="row align-items-center mb-5 pb-5 flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Sistema de Notificações Inteligente</h3>
                    <p class="lead text-muted mb-4">
                        Receba lembretes automáticos por email sobre notas próximas do vencimento.
                        Configure quando e como deseja ser notificado.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Notificações por email</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Configure dias de antecedência</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Central de notificações</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Totalmente personalizável</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/notificacoes.png') }}" alt="">
                    </div>
                </div>
            </div>

            {{-- Feature 3: Organização --}}
            <div class="row align-items-center mb-5 pb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon">
                        <i class="bi bi-tags"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Organização Poderosa</h3>
                    <p class="lead text-muted mb-4">
                        Categorize suas notas, adicione tags, filtre por prioridade e vencimento.
                        Encontre o que precisa em segundos.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Categorias personalizadas</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Tags ilimitadas
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Filtros avançados
                        </li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Busca rápida</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/categoriaTags.png') }}" alt="">
                    </div>
                </div>
            </div>
            {{-- Feature 4: Estatísticas --}}
            <div class="row align-items-center mb-5 pb-5 flex-lg-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Dashboard com Estatísticas</h3>
                    <p class="lead text-muted mb-4">
                        Visualize suas notas de forma inteligente com gráficos interativos.
                        Acompanhe seu progresso e produtividade.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Gráficos por categoria</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Análise por prioridade</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Status de notas (concluídas, pendentes, vencidas)</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Filtros por período (mês/ano)</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/estatisticas.png') }}" alt="Dashboard de Estatísticas">
                    </div>
                </div>
            </div>

            {{-- Feature 4: Lixeira --}}
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="feature-icon">
                        <i class="bi bi-trash"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Lixeira com Recuperação</h3>
                    <p class="lead text-muted mb-4">
                        Deletou sem querer? Sem problemas! Recupere notas excluídas em até 30 dias.
                        Segurança e tranquilidade para você.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Recuperação de até 30 dias</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Exclusão automática após prazo</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Visualizar itens deletados</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Restauração com um clique</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="screenshot-placeholder">
                        <img src="{{ asset('img/lixeira.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Como Funciona --}}
    <section id="como-funciona" class="feature-section bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Como Funciona</h2>
                <p class="section-subtitle">Simples e intuitivo em 3 passos</p>
            </div>

            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mx-auto">
                        <span class="fw-bold" style="font-size: 2rem;">1</span>
                    </div>
                    <h4 class="fw-bold mb-3">Crie sua conta</h4>
                    <p class="text-muted">
                        Cadastro rápido e gratuito. Comece a usar em menos de 1 minuto.
                    </p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mx-auto">
                        <span class="fw-bold" style="font-size: 2rem;">2</span>
                    </div>
                    <h4 class="fw-bold mb-3">Crie suas notas</h4>
                    <p class="text-muted">
                        Adicione notas, organize com categorias e tags, defina prazos.
                    </p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <div class="feature-icon mx-auto">
                        <span class="fw-bold" style="font-size: 2rem;">3</span>
                    </div>
                    <h4 class="fw-bold mb-3">Receba lembretes</h4>
                    <p class="text-muted">
                        Nunca mais esqueça nada. Receba notificações automáticas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <img src="{{ asset('img/borboleta.png') }}" alt="Logo" class="logo-icon img-fluid w-5 me-3">
                    </i> Mindly Notes</h5>
                    <p class="text-white-50">Sistema inteligente de gerenciamento de notas e tarefas.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Links Rápidos</h6>
                    <ul class="list-unstyled">
                        <li><a href="#funcionalidades" class="text-white-50 text-decoration-none">Funcionalidades</a>
                        </li>
                        <li><a href="#como-funciona" class="text-white-50 text-decoration-none">Como Funciona</a></li>
                        @guest
                            <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Entrar</a></li>
                            <li><a href="{{ route('register') }}"
                                    class="text-white-50 text-decoration-none">Cadastrar</a>
                            </li>
                        @endguest
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Projeto</h6>
                    <p class="text-white-50 small">
                        Desenvolvido como Trabalho de Conclusão de Curso (TCC).
                    </p>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center text-white-50">
                <small>&copy; 2025 Mindly Notes. Todos os direitos reservados.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
