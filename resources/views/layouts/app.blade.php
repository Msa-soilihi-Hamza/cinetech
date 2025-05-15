<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/comments.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-video@0.8.0/dist/js/splide-extension-video.min.js"></script>
</head>
<body>
    @include('layouts.navigation')

    <!-- Messages Flash -->
    <div class="fixed top-20 right-4 z-50">
        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg mb-4 flex items-center" id="success-message">
                <span>{{ session('success') }}</span>
                <button class="ml-4 text-white" onclick="this.parentElement.remove()">×</button>
            </div>
            <script>
                setTimeout(() => {
                    const message = document.getElementById('success-message');
                    if (message) message.remove();
                }, 5000);
            </script>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg mb-4 flex items-center" id="error-message">
                <span>{{ session('error') }}</span>
                <button class="ml-4 text-white" onclick="this.parentElement.remove()">×</button>
            </div>
            <script>
                setTimeout(() => {
                    const message = document.getElementById('error-message');
                    if (message) message.remove();
                }, 5000);
            </script>
        @endif
    </div>

    <turbo-frame id="content">
        <main>
            @yield('content')
        </main>
    </turbo-frame>

    @stack('scripts')

    <script src="{{ asset('js/favorites.js') }}"></script>
    <script src="{{ asset('js/comments.js') }}"></script>
    
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init();
        });
    </script>

    <script>
    // Gestion des états actifs des liens
    document.addEventListener('turbo:load', function() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('nav a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
    </script>
</body>
</html>
