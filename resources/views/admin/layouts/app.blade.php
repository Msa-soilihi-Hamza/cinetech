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

        main {
            background-color: var(--text-light);
            padding: 80px 2rem 2rem;
            margin-top: 0;
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
                    <i class="ri-movie-2-line me-2"></i>
                    <span>CINETECH</span>
                    <small class="d-block">Administration</small>
                </div>
                
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="ri-user-3-line"></i>Utilisateurs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/comments*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
                            <i class="ri-chat-3-line"></i>Commentaires
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="ri-home-4-line"></i>Site public
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent logout-btn">
                                <i class="ri-logout-box-line"></i>Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="header">
            <h1 class="h2">@yield('header', 'Administration Cinetech')</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
