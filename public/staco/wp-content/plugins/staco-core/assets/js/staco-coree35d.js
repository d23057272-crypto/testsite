function Staco_Core_scripts_load() {
    var $ = jQuery;

}

(function ($) {
    "use strict";

    // Window Load+Resize
    $(window).on('load resize', function () {
        // Elementor Frontend Load
        $(window).on('elementor/frontend/init', function () {
            if (elementorFrontend.isEditMode()) {
                elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
                    Staco_Core_scripts_load();
                });
            }
        });
    });

    // Window Load
    $(window).on('load', function () {
        Staco_Core_scripts_load();
    });

})(jQuery);