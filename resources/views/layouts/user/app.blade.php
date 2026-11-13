
@props(['title' => 'Home'])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true'
            || (
                localStorage.getItem('darkMode') === null
                && window.matchMedia('(prefers-color-scheme: dark)').matches
            )
    }"
    :class="{ 'dark': darkMode }"
    x-init="
        document.documentElement.classList.toggle('dark', darkMode);
        $watch('darkMode', val => {
            document.documentElement.classList.toggle('dark', val);
            localStorage.setItem('darkMode', val);
        });
    "
>
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
    >

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site_name') }}</title>

    <link
        rel="icon"
        href="{{ asset(setting('site_favicon')) }}"
        type="image/x-icon"
    >

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet"
    >

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Pako -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>

    <!-- TradingView -->
    <script src="https://s3.tradingview.com/tv.js"></script>

    <!-- Custom Styles -->
    <link
        rel="stylesheet"
        href="{{ asset('css/scrollbar.css') }}"
    >

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'trust-blue': '#3375BB',
                        'trust-blue-dark': '#2C579B',
                        'trust-bg': '#F5F8FF',
                        'trust-text': '#0B0B0B',
                        'trust-gray': '#F0F3FA',
                        'trust-text-secondary': '#737373'
                    }
                }
            }
        };
    </script>

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        /* Smooth dark mode transitions */
        * {
            transition:
                background-color 0.3s ease,
                color 0.3s ease,
                border-color 0.3s ease;
        }

        /* Prevent Alpine.js content flash */
        [x-cloak] {
            display: none !important;
        }

        /* Telegram Chat */
        #telegram-chat {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            background-color: #0088cc;
            border-radius: 50%;
            padding: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        #telegram-chat:hover {
            transform: scale(1.1);
        }

        #telegram-chat img {
            width: 32px;
            height: 32px;
        }
    </style>

</head>

<body class="bg-white dark:bg-gray-900 text-trust-text dark:text-gray-100 min-h-screen">

    <!-- Desktop Sidebar -->
    @include('layouts.user.sidebar')

    <!-- Main Content -->
    <div class="md:ml-64 transition-all duration-300 content-area">

        <!-- Navigation -->
        @include('layouts.user.navigation')

        <!-- Main Content Area -->
        <main class="p-4 md:p-6 bg-white dark:bg-gray-800 pb-24 md:pb-6">

            {{ $slot }}

            <!-- Modals -->
            @include('layouts.user.modal')

        </main>

    </div>

    <!-- Mobile Navigation -->
    @include('layouts.user.mobile-navigation')

    <!-- Stacked Page Scripts -->
    @stack('scripts')

    <!-- Livewire Scripts (Livewire 3 includes Alpine.js) -->
    @livewireScripts

    <!-- Main Application JavaScript -->
    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
