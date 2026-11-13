<!DOCTYPE html>
<html x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      :class="{ 'dark': darkMode }" 
      lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
         <title>{{ (setting('site_name')) }}</title>
    <link rel="icon" href="{{ asset(setting('site_favicon')) }}" type="image/x-icon">

        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    <!-- Vite Compiled CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
      @livewireStyles
    </head>
    <body class="font-sans antialiased">
        {{ $slot }}
		<!-- Livewire Scripts -->
    @livewireScripts
        <script>
            // Initialize dark mode from localStorage
            if (localStorage.getItem('darkMode') === null) {
                localStorage.setItem('darkMode', window.matchMedia('(prefers-color-scheme: dark)').matches);
            }
        </script>
        
    </body>
    
</html>