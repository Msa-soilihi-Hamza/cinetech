<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administration Cinetech')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --background-light: #f8f9fa;
            --text-light: #ffffff;
            --text-dark: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-light);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), #34495e);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: var(--text-light) !important;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .navbar-brand small {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .nav-menu {
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: var(--text-light);
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            color: var(--secondary-color);
            background: rgba(255, 255, 255, 0.15);
            box-shadow: inset 4px 0 0 var(--secondary-color);
        }

        .nav-link i {
            font-size: 1.2rem;
        }

        .logout-btn {
            color: var(--accent-color) !important;
        }

        .logout-btn:hover {
            color: #c0392b !important;
        }

        /* Responsive menu */
        @media (max-width: 991.98px) {
            .nav-menu {
                display: none;
            }

            .mobile-nav {
                display: flex;
                align-items: center;
                gap: 2rem;
            }

            .mobile-menu-btn {
                display: flex;
                align-items: center;
                color: var(--text-light);
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
            }

            .mobile-menu {
                display: none;
                position: fixed;
                top: 60px;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, var(--primary-color), #34495e);
                padding: 1rem;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                z-index: 1001;
                overflow-y: auto;
            }

            .mobile-menu.active {
                display: block;
            }

            .mobile-menu-header {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1rem;
            }

            .mobile-menu-close {
                background: none;
                border: none;
                color: var(--text-light);
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
            }

            .mobile-menu .nav-menu {
                display: block;
                padding: 0;
                margin: 0;
            }

            .mobile-menu .nav-item {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .mobile-menu .nav-link {
                width: 100%;
                text-align: left;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .mobile-menu .nav-link i {
                font-size: 1.2rem;
            }

            .mobile-menu .nav-link span {
                flex: 1;
            }

            .mobile-menu .nav-link:hover {
                color: var(--text-light);
                background: rgba(255, 255, 255, 0.1);
            }

            .mobile-menu .nav-link.active {
                color: var(--secondary-color);
                background: rgba(255, 255, 255, 0.15);
                box-shadow: inset 4px 0 0 var(--secondary-color);
            }

            .mobile-menu .logout-btn {
                color: var(--accent-color) !important;
            }

            .mobile-menu .logout-btn:hover {
                color: #c0392b !important;
            }
        }

        main {
            background-color: var(--text-light);
            padding: 80px 2rem 2rem;
            margin-top: 60px;
        }

        @media (max-width: 992px) {
            .nav-menu {
                display: none;
            }
            
            .nav-mobile {
                display: flex;
                gap: 1rem;
            }

            main {
                padding: 80px 1rem 1rem;
            }
        }

        .header {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 2px solid var(--background-light);
            padding-bottom: 0.5rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="navbar-brand">
                    <i class="ri-film-line"></i>
                    <span>Cinetech</span>
                    <small>Administration</small>
                </div>

                <div class="mobile-nav">
                    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="d-none d-md-block">
                    <div class="d-flex gap-2">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="fas fa-home"></i>
                            <span>Site public</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menu mobile -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <button class="mobile-menu-close" id="mobileMenuClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->is('admin/comments*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Commentaires</span>
                </a>
            </li>
            <li class="nav-item d-md-none">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Site public</span>
                </a>
            </li>
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <main class="container-fluid mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">@yield('header')</h2>
            </div>
        </div>
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuClose = document.getElementById('mobileMenuClose');

            // Ouvrir le menu
            mobileMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.add('active');
            });

            // Fermer avec le bouton ×
            mobileMenuClose.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
            });

            // Fermer en cliquant en dehors
            document.addEventListener('click', function(e) {
                if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    mobileMenu.classList.remove('active');
                }
            });

            // Empêcher la propagation du clic sur les liens du menu
            mobileMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
