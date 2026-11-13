
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => 'Home']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => 'Home']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<!DOCTYPE html>
<html
    lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"
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

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(setting('site_name')); ?></title>

    <link
        rel="icon"
        href="<?php echo e(asset(setting('site_favicon'))); ?>"
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
        href="<?php echo e(asset('css/scrollbar.css')); ?>"
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
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


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
    <?php echo $__env->make('layouts.user.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Main Content -->
    <div class="md:ml-64 transition-all duration-300 content-area">

        <!-- Navigation -->
        <?php echo $__env->make('layouts.user.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Main Content Area -->
        <main class="p-4 md:p-6 bg-white dark:bg-gray-800 pb-24 md:pb-6">

            <?php echo e($slot); ?>


            <!-- Modals -->
            <?php echo $__env->make('layouts.user.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </main>

    </div>

    <!-- Mobile Navigation -->
    <?php echo $__env->make('layouts.user.mobile-navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Stacked Page Scripts -->
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Livewire Scripts (Livewire 3 includes Alpine.js) -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <!-- Main Application JavaScript -->
    <script src="<?php echo e(asset('js/main.js')); ?>"></script>

</body>
</html>
<?php /**PATH C:\Tools\covachain.org\resources\views/layouts/user/app.blade.php ENDPATH**/ ?>