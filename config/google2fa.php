<?php

// config/google2fa.php

return [
    /*
     * Enable or disable the authentication using 2FA
     */
    'enabled' => true,

    /*
     * Lifetime of the session 2FA verification in minutes
     */
    'lifetime' => 60,

    /*
     * Number of digits for the 2FA code
     */
    'digits' => 6,

    /*
     * Window of time in which codes are valid (in seconds)
     */
    'window' => 1,

    /*
     * Hash user passwords when storing them
     */
    'encrypt' => true,
];