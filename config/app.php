<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Supported Locales for User Selection
    |--------------------------------------------------------------------------
    |
    | These are the languages the user can choose from in the settings.
    | Keys must match your translation directories (e.g., 'es' => resources/lang/es/).
    |
    */

    'supported_locales' => [
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'am' => 'አማርኛ',
        'ar' => 'العربية',
        'as' => 'অসমীয়া',
        'az' => 'Azərbaycanca',
        'be' => 'Беларуская',
        'bg' => 'Български',
        'bho' => 'भोजपुरी',
        'bm' => 'Bamanankan',
        'bn' => 'বাংলা',
        'bs' => 'Bosanski',
        'ca' => 'Català',
        'ceb' => 'Cebuano',
        'ckb' => 'کوردیی ناوەندی',
        'cs' => 'Čeština',
        'cy' => 'Cymraeg',
        'da' => 'Dansk',
        'de' => 'Deutsch',
        'de_CH' => 'Schweizer Hochdeutsch',
        'doi' => 'डोगरी',
        'ee' => 'Eʋegbe',
        'el' => 'Ελληνικά',
        'en' => 'English',
        'en_CA' => 'Canadian English',
        'eo' => 'Esperanto',
        'es' => 'Español',
        'et' => 'Eesti',
        'eu' => 'Euskara',
        'fa' => 'فارسی',
        'fi' => 'Suomi',
        'fil' => 'Filipino',
        'fr' => 'Français',
        'fy' => 'Frysk',
        'ga' => 'Gaeilge',
        'gd' => 'Gàidhlig',
        'gl' => 'Galego',
        'gu' => 'ગુજરાતી',
        'ha' => 'Hausa',
        'haw' => 'ʻŌlelo Hawaiʻi',
        'he' => 'עברית',
        'hi' => 'हिन्दी',
        'hr' => 'Hrvatski',
        'hu' => 'Magyar',
        'hy' => 'Հայերեն',
        'id' => 'Bahasa Indonesia',
        'ig' => 'Igbo',
        'is' => 'Íslenska',
        'it' => 'Italiano',
        'ja' => '日本語',
        'ka' => 'ქართული',
        'kk' => 'Қазақша',
        'km' => 'ខ្មែរ',
        'kn' => 'ಕನ್ನಡ',
        'ko' => '한국어',
        'ku' => 'Kurdî',
        'ky' => 'Кыргызча',
        'lb' => 'Lëtzebuergesch',
        'lg' => 'Luganda',
        'ln' => 'Lingála',
        'lo' => 'ລາວ',
        'lt' => 'Lietuvių',
        'lv' => 'Latviešu',
        'mai' => 'मैथिली',
        'mg' => 'Malagasy',
        'mi' => 'Māori',
        'mk' => 'Македонски',
        'ml' => 'മലയാളം',
        'mn' => 'Монгол',
        'mni_Mtei' => 'ꯃꯤꯇꯩꯂꯣꯟ',
        'mr' => 'मराठी',
        'ms' => 'Bahasa Melayu',
        'mt' => 'Malti',
        'my' => 'မြန်မာ',
        'nb' => 'Norsk Bokmål',
        'ne' => 'नेपाली',
        'nl' => 'Nederlands',
        'nn' => 'Norsk Nynorsk',
        'oc' => 'Occitan',
        'om' => 'Afaan Oromoo',
        'or' => 'ଓଡ଼ିଆ',
        'pa' => 'ਪੰਜਾਬੀ',
        'pl' => 'Polski',
        'ps' => 'پښتو',
        'pt' => 'Português',
        'qu' => 'Runasimi',
        'ro' => 'Română',
        'ru' => 'Русский',
        'rw' => 'Ikinyarwanda',
        'sa' => 'संस्कृतम्',
        'sc' => 'Sardu',
        'sd' => 'سنڌي',
        'si' => 'සිංහල',
        'sk' => 'Slovenčina',
        'sl' => 'Slovenščina',
        'sn' => 'ChiShona',
        'so' => 'Soomaali',
        'sq' => 'Shqip',
        'sr_Cyrl' => 'Српски (ћирилица)',
        'sr_Latn' => 'Srpski (latinica)',
        'sr_Latn_ME' => 'Crnogorski',
        'su' => 'Basa Sunda',
        'sv' => 'Svenska',
        'sw' => 'Kiswahili',
        'ta' => 'தமிழ்',
        'te' => 'తెలుగు',
        'tg' => 'Тоҷикӣ',
        'th' => 'ไทย',
        'ti' => 'ትግርኛ',
        'tk' => 'Türkmençe',
        'tl' => 'Tagalog',
        'tr' => 'Türkçe',
        'tt' => 'Татарча',
        'ug' => 'ئۇيغۇرچە',
        'uk' => 'Українська',
        'ur' => 'اردو',
        'uz_Cyrl' => 'Ўзбекча (кирилл)',
        'uz_Latn' => 'Oʻzbekcha (lotin)',
        'vi' => 'Tiếng Việt',
        'xh' => 'isiXhosa',
        'yi' => 'ייִדיש',
        'yo' => 'Yorùbá',
        'zh_CN' => '简体中文',
        'zh_HK' => '繁體中文（香港）',
        'zh_TW' => '繁體中文',
        'zu' => 'isiZulu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        // Laravel Framework Service Providers...
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        

        // Application Service Providers...
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\SettingsServiceProvider::class,
        App\Providers\ResendMailServiceProvider::class,
    ],
];