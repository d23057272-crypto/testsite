/*-----------------------------------------------------------------------------
Theme Name: staco
Author: uigigs
Author URL: https://themeforest.net/user/uigigs/portfolio
-----------------------------------------------------------------------------*/
(function ($) {
    ("use strict");

    var Staco = {
        init: function () {
            this.Basic.init();
        },
        Basic: {
            init: function () {
                this.countDown();
            },

            countDown: function () {
                if (typeof $.fn.countdown == "function") {
                    try {
                        var eventCountdownTime = $(".staco-countdown").data("countdown_time"),
                            day = StacoObj.day == "Day" ? "Day%!D" : StacoObj.day,
                            hour = StacoObj.hour == "Hour" ? "Hour%!D" : StacoObj.hour,
                            minute = StacoObj.minute == "Minute" ? "Minute%!D" : StacoObj.minute,
                            second = StacoObj.second == "Second" ? "Second%!D" : StacoObj.second;
                        $(".staco-countdown")
                            .countdown(eventCountdownTime)
                            .on("update.countdown", function (event) {
                                $(this).html(
                                    event.strftime(
                                        "" +
                                            '<div class="countdown-section"><h3>%D</h3></div>' +
                                            '<div class="countdown-section"><h3>%H</h3></div>' +
                                            '<div class="countdown-section"><h3>%M</h3></div>' +
                                            '<div class="countdown-section"><h3>%S</h3></div>'
                                    )
                                );
                            })
                            .on("finish.countdown", function (event) {
                                $(this).html(event.strftime(""));
                            });
                    } catch (err) {
                        console.log("Event Countdown : " + err.message);
                    }
                }
            },
        },
    };

    var footerText = $(".token-footer-bottom-text .text .top");
    if (footerText.length) {
        $(window).on("scroll", function () {
            var cryptoBannerImg = $(".token-footer-bottom-text .text .top");
            var y = window.scrollY;
            var x;
            x = cryptoBannerImg.offset().top;
            x = x - 800;

            let animationValue = 1;
            animationValue = (y - x) / 2;

            let animationStop = 0;

            if (animationValue < 0) {
                animationValue > 0;
            }

            if (animationValue > animationStop) {
                animationValue = animationStop;
            }

            cryptoBannerImg.css("top", `${animationValue}px`);
        });
    }

    if ($("#primary").find("div.staco-masonry-grid").length !== 0) {
        var grid = $(".staco-masonry-grid").imagesLoaded(function () {
            $grid = grid.isotope({
                // set itemSelector so .grid-sizer is not used in layout
                itemSelector: ".staco-grid-item",
                percentPosition: true,
                isAnimated: true,
                masonry: {
                    columnWidth: ".staco-grid-item",
                    horizontalOrder: true,
                },
                animationOptions: {
                    duration: 700,
                    easing: "linear",
                    queue: false,
                },
            });
        });
    }
    var sidebarBtn = $(".sidebarBtn");
    if (sidebarBtn.length) {
        $(".mean-bar .sidebarBtn").on("click", function (e) {
            e.preventDefault();
            if ($(".mobile-slide-nav").is(":visible")) {
                $(".mobile-slide-nav").slideUp();
                $("body").removeClass("slidemenuon");
            } else {
                $(".mobile-slide-nav").slideDown();
                $("body").addClass("slidemenuon");
            }
        });
    }

    /*-- sticky header scripts start --*/
     function stacoScroll() {
        let lastScroll = 100;
        $(window).on("scroll", function () {

            if ($(window).scrollTop() >= $("body").offset().top + 50) {
                $("body").addClass("mn-top");
            } else {
                $("body").removeClass("mn-top");
            }

            let currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
            let diffScroll = currentScroll - lastScroll;
            if (diffScroll > 0 || currentScroll == 0) {
                $(".staco_sticky > div > .elementor-section:first-child").removeClass("header-section sticky-header");
                $(".staco_sticky > div.sticky-header-height-fix").removeClass("sticky-header-height");
                $("body").removeClass("nav-expanded");
            } else {
                $(".staco_sticky > div > .elementor-section:first-child").addClass("header-section sticky-header");
                $(".staco_sticky > div.sticky-header-height-fix").addClass("sticky-header-height");
            }
            lastScroll = currentScroll;
        });
    }

    $(function () {
        $(".first-tab-open .accordion-item:first-child .accordion-collapse").addClass("show");
    });

    // masonary

    $(document).ready(function () {
        // typing text animation script
        var typing = $(".typing");
        if (typing.length) {
            var typed = new Typed(".typing", {
                strings: ["Email", "Meeting", "Invoice"],
                typeSpeed: 100,
                backSpeed: 60,
                loop: true,
            });
        }
    });

    /*--  Side Bar Sticky js Start--*/
    var sidebarSticky = $(".sidebar");
        if (sidebarSticky.length) {
            var sidebar = new StickySidebar(".sidebar", {
             topSpacing: 20,
             bottomSpacing: 20,
             containerSelector: ".main-content",
             innerWrapperSelector: ".sidebar__inner",
        });
     }
     /*--  Side Bar Sticky js End --*/

    stacoScroll();
    /*-- sticky header scripts end --*/

    /*-- menu responsive dropdown scripts start --*/
    $(".main-menu > li").on("click", function () {
        if ($(this).find(".submenu")) {
            $(this).find(".submenu").toggleClass("show");
        }
    });
    /*-- menu responsive dropdown scripts end --*/

    /*-- canvas menu scripts start --*/
    var navexpander = $("#nav-expander");
    if (navexpander.length) {
        $("#nav-expander").on("click", function (e) {
            e.preventDefault();
            $("body").toggleClass("nav-expanded");
        });
    }

    $(".mobile-navbar-menu a").each(function () {
        var href = $(this).attr("href");
        if ((href = "#")) {
            $(this).addClass("hash");
        } else {
            $(this).removeClass("hash");
        }
    });

    $.fn.menumaker = function (options) {
        var mobile_menu = $(this),
            settings = $.extend(
                {
                    format: "dropdown",
                    sticky: false,
                },
                options
            );

        return this.each(function () {
            mobile_menu.find("li ul").parent().addClass("has-sub");
            var multiTg = function () {
                mobile_menu.find(".has-sub").prepend('<span class="submenu-button"><em></em></span>');
                mobile_menu.find(".hash").parent().addClass("hash-has-sub");
                mobile_menu.find(".submenu-button").on("click", function () {
                    if ($(this).parent().siblings("li").hasClass("active")) {
                        $(this).parent().siblings("li").removeClass("active");
                        $(this).parent().siblings("li").find(".submenu-button").removeClass("submenu-opened");
                        $(this).parent().addClass("active");
                        $(this).addClass("submenu-opened");
                        if (
                            $(this).parent().siblings("li").find(".submenu-button").siblings("ul").hasClass("open-sub")
                        ) {
                            $(this)
                                .parent()
                                .siblings("li")
                                .find(".submenu-button")
                                .siblings("ul")
                                .removeClass("open-sub")
                                .hide("fadeIn");
                            $(this).parent().siblings("li").find(".submenu-button").siblings("ul").hide("fadeIn");
                        } else {
                            $(this)
                                .parent()
                                .siblings("li")
                                .find(".submenu-button")
                                .siblings("ul")
                                .addClass("open-sub")
                                .hide("fadeIn");
                            $(this)
                                .parent()
                                .siblings("li")
                                .find(".submenu-button")
                                .siblings("ul")
                                .slideToggle()
                                .show("fadeIn");
                        }

                        if ($(this).siblings("ul").hasClass("open-sub")) {
                            $(this).siblings("ul").removeClass("open-sub").hide("fadeIn");
                            $(this).siblings("ul").hide("fadeIn");
                            $(this).parent().removeClass("active");
                            $(this).removeClass("submenu-opened");
                        } else {
                            $(this).siblings("ul").addClass("open-sub").hide("fadeIn");
                            $(this).siblings("ul").slideToggle().show("fadeIn");
                        }
                    } else {
                        $(this).parent().addClass("active");
                        $(this).addClass("submenu-opened");
                        if ($(this).siblings("ul").hasClass("open-sub")) {
                            $(this).siblings("ul").removeClass("open-sub").hide("fadeIn");
                            $(this).siblings("ul").hide("fadeIn");
                            $(this).parent().removeClass("active");
                            $(this).removeClass("submenu-opened");
                        } else {
                            $(this).siblings("ul").addClass("open-sub").hide("fadeIn");
                            $(this).siblings("ul").slideToggle().show("fadeIn");
                        }
                    }
                });
            };

            if (settings.format === "multitoggle") multiTg();
            else mobile_menu.addClass("dropdown");
            if (settings.sticky === true) mobile_menu.css("position", "fixed");
            var resizeFix = function () {
                if ($(window).width() > 991) {
                    mobile_menu.find("ul").show("fadeIn");
                    mobile_menu.find("ul.sub-menu").hide("fadeIn");
                }
            };
            resizeFix();
            return $(window).on("resize", resizeFix);
        });
    };

    $(document).ready(function () {
        $("#mobile-navbar-menu").menumaker({
            format: "multitoggle",
        });
    });
    /*-- canvas menu scripts end --*/


    function MobileNavigation() {
        var a = $('.offscreen-navigation .main-menu');
        if (a.length) {
            $(".menu-item-has-children").append("<span></span>");
            $(".page_item_has_children").append("<span></span>");

            a.children("li").addClass("menu-item-parent");

            $('.menu-item-has-children > span').on('click', function () {
                var _self = $(this),
                    sub_menu = _self.parent().find('>.sub-menu');
                if (_self.hasClass('open')) {
                    sub_menu.slideUp();
                    _self.removeClass('open');
                } else {
                    sub_menu.slideDown();
                    _self.addClass('open');
                }
            });
            $('.page_item_has_children > span').on('click', function () {
                var _self = $(this),
                    sub_menu = _self.parent().find('>.children');
                if (_self.hasClass('open')) {
                    sub_menu.slideUp();
                    _self.removeClass('open');
                } else {
                    sub_menu.slideDown();
                    _self.addClass('open');
                }
            });
        }
        $('.mean-bar .sidebarBtn').on('click', function (e) {
            e.preventDefault();
            if ($('.rt-slide-nav').is(":visible")) {
                $('.rt-slide-nav').slideUp();
                $('body').removeClass('slidemenuon');
            } else {
                $('.rt-slide-nav').slideDown();
                $('body').addClass('slidemenuon');
            }
        });
    }
    MobileNavigation();

    /*-- staco scroll top scripts start --*/
    var stacoScrollTop = document.querySelector(".staco-scroll-top");
    if (stacoScrollTop != null) {
        var scrollProgressPatch = document.querySelector(".staco-scroll-top path");
        var pathLength = scrollProgressPatch.getTotalLength();
        var offset = 50;
        scrollProgressPatch.style.transition = scrollProgressPatch.style.WebkitTransition = "none";
        scrollProgressPatch.style.strokeDasharray = pathLength + " " + pathLength;
        scrollProgressPatch.style.strokeDashoffset = pathLength;
        scrollProgressPatch.getBoundingClientRect();
        scrollProgressPatch.style.transition = scrollProgressPatch.style.WebkitTransition =
            "stroke-dashoffset 10ms linear";
        window.addEventListener("scroll", function (event) {
            var scroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var progress = pathLength - (scroll * pathLength) / height;
            scrollProgressPatch.style.strokeDashoffset = progress;
            var scrollElementPos = document.body.scrollTop || document.documentElement.scrollTop;
            if (scrollElementPos >= offset) {
                stacoScrollTop.classList.add("progress-done");
            } else {
                stacoScrollTop.classList.remove("progress-done");
            }
        });
        stacoScrollTop.addEventListener("click", function (e) {
            e.preventDefault();
            window.scroll({
                top: 0,
                left: 0,
                behavior: "smooth",
            });
        });
    }
    /*-- staco scroll top scripts end --*/

    /*-- corporate testimonial slider js start --*/
    var scrollTop = $(".staco-scroll-top");
    if (scrollTop.length) {
        /*-- sticky header scripts start --*/
        function stacoScroll() {
            let lastScroll = 0;
            $(window).on("scroll", function () {
                let currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
                let diffScroll = currentScroll - lastScroll;
                if (diffScroll > 0 || currentScroll == 0) {
                    $(".staco-scroll-top").removeClass("fixed");
                    $(".staco-scroll-top").removeClass("fixed");
                } else {
                    $(".staco-scroll-top").addClass("fixed");
                    $(".staco-scroll-top").addClass("fixed");
                }
                lastScroll = currentScroll;
            });
        }

        stacoScroll();
        /*-- sticky header scripts end --*/
    }
    /*-- corporate testimonial slider js end --*/

    /*-- venobox start --*/
    var myVideoLink = $(".my-video-links");
    if (myVideoLink.length) {
        new VenoBox({
            selector: ".my-video-links",
        });
    }
    /*-- venobox end --*/

    /*-- image rotate when scrolling scripts end --*/

    /*-- marketingImg counter scroll effect start --*/
    var marketingImg = $(".marketing-img.v2");
    if (marketingImg.length) {
        let isAnimated = 0;
        function counterEffect() {
            if (isAnimated == 0) {
                const counterItem = document.querySelectorAll(".counter");
                counterItem.forEach((item) => {
                    var counterText = item.innerText;
                    item.innerText = "0";
                    const updateCounter = () => {
                        let dataTarget = +item.getAttribute("data-target");
                        if (dataTarget > 999) {
                            dataTarget = dataTarget / 1000;
                        }
                        counterText = +item.innerText;
                        let increment = dataTarget / 1000;
                        if (counterText < dataTarget) {
                            item.innerText = `${Math.ceil(counterText + increment)}`;
                            setTimeout(updateCounter, 1);
                        }
                    };
                    updateCounter();
                });
            }
        }
        $(window).on("scroll", function () {
            var counterItem = $(".marketing-img.v2");
            var y = window.scrollY;
            var x = counterItem.offset().top;
            x = x - 400;

            if (y > x && y < x - 400 + screen.height) {
                counterEffect();
                isAnimated++;
            } else {
                isAnimated = 0;
            }
        });
    }
    /*-- marketingImg counter scroll effect end --*/

    /*-- circular progress start --*/
    var pieProgress = $(".pie_progress");
    if (pieProgress.length) {
        $(".pie_progress").asPieProgress({
            namespace: "pie_progress",
            goal: 100,
            min: 0,
            max: 100,
            speed: 15,
            barcolor: "#00CEC9",
            barsize: "10",
            trackcolor: "#00CEC933",
            easing: "linear",
        });
        $(".pie_progress").asPieProgress("start");
    }
    /*-- circular progress end --*/

    /*-- v7 Banner text slider End--*/
        const swiper = new Swiper(".swiper-container", {
        slideToClickedSlide: true,
        slidePerView: "auto",
        freeMode: {
            enabled: true,
            sticky: true,
            momentumBounce: false,
        },
        scrollbar: {
            el: ".swiper-scrollbar",
            draggable: true,
            dragSize: 100,
        },
        mousewheel: {
            enabled: true,
            sensitivity: 5.5,
        },
    });

    /*-- index2-statistics-content section start --*/
    var index2StatisticsContent = $(".index2-statistics-content");
    if (index2StatisticsContent.length) {
        let isAnimated = 0;
        function counterEffect() {
            if (isAnimated == 0) {
                const counterItem = document.querySelectorAll(".counter");
                counterItem.forEach((item) => {
                    var counterText = item.innerText;
                    item.innerText = "0";
                    const updateCounter = () => {
                        let dataTarget = +item.getAttribute("data-target");
                        if (dataTarget > 999) {
                            dataTarget = dataTarget / 1000;
                        }
                        counterText = +item.innerText;
                        let increment = dataTarget / 1000;
                        if (counterText < dataTarget) {
                            item.innerText = `${Math.ceil(counterText + increment)}`;
                            setTimeout(updateCounter, 1);
                        }
                    };
                    updateCounter();
                });
            }
        }
        $(window).on("scroll", function () {
            var counterItem = $(".index2-statistics-content");
            var y = window.scrollY;
            var x = counterItem.offset().top;
            x = x - 400;

            if (y > x && y < x - 400 + screen.height) {
                counterEffect();
                isAnimated++;
            } else {
                isAnimated = 0;
            }
        });
    }
    /*-- index2-statistics-content section end --*/

    var aboutFeatureImg = $(".about-feature-img");
    if (aboutFeatureImg.length) {
        let isAnimated = 0;
        function counterEffect() {
            if (isAnimated == 0) {
                const counterItem = document.querySelectorAll(".counter");
                counterItem.forEach((item) => {
                    var counterText = item.innerText;
                    item.innerText = "0";
                    const updateCounter = () => {
                        let dataTarget = +item.getAttribute("data-target");
                        if (dataTarget > 999) {
                            dataTarget = dataTarget / 1000;
                        }
                        counterText = +item.innerText;
                        let increment = dataTarget / 1000;
                        if (counterText < dataTarget) {
                            item.innerText = `${Math.ceil(counterText + increment)}`;
                            setTimeout(updateCounter, 1);
                        }
                    };
                    updateCounter();
                });
            }
        }
        $(window).on("scroll", function () {
            var counterItem = $(".about-feature-img");
            var y = window.scrollY;
            var x = counterItem.offset().top;
            x = x - 400;

            if (y > x && y < x - 400 + screen.height) {
                counterEffect();
                isAnimated++;
            } else {
                isAnimated = 0;
            }
        });
    }
    /*-- about feature section end --*/

    /*-- pricing selector scripts start --*/
    var pricingSelector = $(".pricing-selector");
    if (pricingSelector.length) {
        $(".pricing-monthly-btn").on("click", function () {
            $("#pricing-selector").prop("checked", false);
            $(".pricing-monthly").css("display", "block");
            $(".pricing-yearly").css("display", "none");
        });

        $(".pricing-yearly-btn").on("click", function () {
            $("#pricing-selector").prop("checked", true);
            $(".pricing-monthly").css("display", "none");
            $(".pricing-yearly").css("display", "block");
        });

        $("#pricing-selector").on("change", function () {
            if (this.checked) {
                $(".pricing-monthly").css("display", "none");
                $(".pricing-yearly").css("display", "block");
            } else {
                $(".pricing-monthly").css("display", "block");
                $(".pricing-yearly").css("display", "none");
            }
        });
    }
    /*-- pricing selector scripts end --*/

    /*-- feature card scripts start --*/
    var featureCard = $(".feature-card");
    if (featureCard.length) {
        $(".feature-card").hover(
            function () {
                $(this).addClass("active");
                $(".feature-card.card-active").removeClass("active");
            },
            function () {
                $(this).removeClass("active");
                $(".feature-card.card-active").addClass("active");
            }
        );
    }
    /*-- feature card scripts start --*/

    /*-- best pricing selector scripts start --*/
    var bestPricingSelector = $(".best-pricing-selector");
    if (bestPricingSelector.length) {
        $(".best-pricing-monthly-btn").on("click", function () {
            $("#best-pricing-selector").prop("checked", false);
            $(".best-pricing-grid.monthly").css("display", "flex");
            $(".best-pricing-grid.yearly").css("display", "none");
            $(".best-pricing-monthly-btn").addClass("active");
            $(".best-pricing-yearly-btn").removeClass("active");
        });

        $(".best-pricing-yearly-btn").on("click", function () {
            $("#best-pricing-selector").prop("checked", true);
            $(".best-pricing-grid.monthly").css("display", "none");
            $(".best-pricing-grid.yearly").css("display", "flex");
            $(".best-pricing-monthly-btn").removeClass("active");
            $(".best-pricing-yearly-btn").addClass("active");
        });

        $("#best-pricing-selector").on("change", function () {
            if (this.checked) {
                $(".best-pricing-grid.monthly").css("display", "none");
                $(".best-pricing-grid.yearly").css("display", "flex");
                $(".best-pricing-monthly-btn").removeClass("active");
                $(".best-pricing-yearly-btn").addClass("active");
            } else {
                $(".best-pricing-grid.monthly").css("display", "flex");
                $(".best-pricing-grid.yearly").css("display", "none");
                $(".best-pricing-monthly-btn").addClass("active");
                $(".best-pricing-yearly-btn").removeClass("active");
            }
        });
    }
    /*-- best pricing selector scripts end --*/

    /*-- compare price card start --*/
    var comparePriceCard = $(".compare-price-card");
    if (comparePriceCard.length) {
        var selectComparePrice = comparePriceCard.find("#select-compare-price");
        var basicPrice = comparePriceCard.find(".basic-price");
        var premiumPrice = comparePriceCard.find(".premium-price");
        var professionalPrice = comparePriceCard.find(".professional-price");

        selectComparePrice.on("change", function () {
            var $option = $(this).find("option:selected");
            var value = $option.val();
            if (value == "basic") {
                basicPrice.css("display", "block");
                premiumPrice.css("display", "none");
                professionalPrice.css("display", "none");
            } else if (value == "premium") {
                basicPrice.css("display", "none");
                premiumPrice.css("display", "block");
                professionalPrice.css("display", "none");
            } else if (value == "professional") {
                basicPrice.css("display", "none");
                premiumPrice.css("display", "none");
                professionalPrice.css("display", "block");
            } else {
                basicPrice.css("display", "block");
                premiumPrice.css("display", "none");
                professionalPrice.css("display", "none");
            }
        });
    }
    /*-- compare price card end --*/

    /*-- counter scrolling effect start --*/
    var counterCard = $(".statistics-text");
    if (counterCard.length) {
        let isAnimated = 0;
        function counterEffect() {
            if (isAnimated == 0) {
                const counterItem = document.querySelectorAll(".counter");
                counterItem.forEach((item) => {
                    var counterText = item.innerText;
                    item.innerText = "0";
                    const updateCounter = () => {
                        let dataTarget = +item.getAttribute("data-target");
                        if (dataTarget > 999) {
                            dataTarget = dataTarget / 1000;
                        }
                        counterText = +item.innerText;
                        let increment = dataTarget / 1000;
                        if (counterText < dataTarget) {
                            item.innerText = `${Math.ceil(counterText + increment)}`;
                            setTimeout(updateCounter, 1);
                        }
                    };
                    updateCounter();
                });
            }
        }
        $(window).on("scroll", function () {
            var counterItem = $(".statistics-text");
            var y = window.scrollY;
            var x = counterItem.offset().top;
            x = x - 400;

            if (y > x && y < x - 400 + screen.height) {
                counterEffect();
                isAnimated++;
            } else {
                isAnimated = 0;
            }
        });
    }
    /*-- counter scrolling effect end --*/

    /*-- hero Section 3 content scripts start --*/
    var heroSection3Content = $(".hero-section-3-content");
    if (heroSection3Content.length) {
        $(window).on("scroll", function () {
            var y = window.scrollY;
            var x = heroSection3Content.offset().top;
            x = x - 400;
            var item = document.querySelectorAll(".hero-section-3-content .item");
            var z = y / 15;
            if (y > x) {
                item[0].style.transform = "translate(-" + z + "px)";
                item[1].style.transform = "translate(-" + z / 2 + "px)";
                item[3].style.transform = "translate(" + z / 2 + "px)";
                item[4].style.transform = "translate(" + z + "px)";
                console.log(z);
            }
        });
    }

    /*-- video start --*/
    var v6BannerImg = $(".v6-banner-img");
    if (v6BannerImg.length) {
        let stacoH6Video = document.getElementById("staco-h6-video");
        let stacoH6VideoControl = document.getElementById("staco-h6-video-control");
        if (stacoH6VideoControl) {
            stacoH6VideoControl.addEventListener("click", () => {
                if (stacoH6Video.paused) {
                    stacoH6Video.play();
                    stacoH6VideoControl.classList.remove("active");
                } else {
                    stacoH6Video.pause();
                    stacoH6VideoControl.classList.add("active");
                }
            });
        }
    }
    /*-- video end --*/

    /*-- contact map start --*/
    var contactMap = $(".contact-map");
    if (contactMap.length) {
        function initialize() {
            $(".contact-map").each(function (index) {
                //Taking data attribute from map wrapper
                var mapLat = parseFloat($(this).data("lat"));
                var mapLng = parseFloat($(this).data("lng"));
                var mapZoom = parseInt($(this).data("zoom"));
                var mapType = $(this).data("maptype");

                //Processing wrapper data attribute to coordinate
                var mapOptions = {
                    center: {
                        lat: mapLat,
                        lng: mapLng,
                    },
                    zoom: mapZoom,
                    mapTypeId: mapType,
                    scrollwheel: false,
                };

                //Initiating map
                var map = new google.maps.Map(this, mapOptions);

                //Map Marker
                var marker = new google.maps.Marker({
                    position: {
                        lat: 40.713355,
                        lng: -74.005535,
                    },
                    map: map,
                    icon: "./assets/images/icons/map-marker.svg",
                });
            });
        }
        google.maps.event.addDomListener(window, "load", initialize);
        initialize();
    }
    /*-- contact map end --*/

    /*-- compare plan js start --*/
    var comparePlansContent = $(".compare-plans-content");
    if (comparePlansContent.length) {
        if ($(window).innerWidth() <= 991) {
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("pricingPlanTabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("pricingPlanTablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Get the element with id="defaultOpen" and click on it
            document.getElementById("pricingPlanTabDefaultOpen").click();
        }
    }
    /*-- compare plan js End --*/

    /*-- pricing Card Js Start --*/
    var pricingPlanSection = $(".pricing-plan-section");
    if (pricingPlanSection.length) {
        $(".pricing-plan-show-btn1").click(function () {
            $(".pricing-plan-show-btn1").toggleClass("active");
            $(".free-card").toggleClass("active");
        });

        $(".pricing-plan-show-btn2").click(function () {
            $(".pricing-plan-show-btn2").toggleClass("active");
            $(".growth-card").toggleClass("active");
        });

        $(".pricing-plan-show-btn3").click(function () {
            $(".pricing-plan-show-btn3").toggleClass("active");
            $(".business-card").toggleClass("active");
        });

        $(".pricing-plan-show-btn4").click(function () {
            $(".pricing-plan-show-btn4").toggleClass("active");
            $(".enterprise-card").toggleClass("active");
        });
    }
    /*-- pricing Card Js End --*/

    /*-- h6-bottom-section scripts start --*/
    var h6BottomSection = $(".h6-bottom-section");
    if (h6BottomSection.length) {
        $(window).on("scroll", function () {
            var item = $(".disclaimer-section");
            var y = window.scrollY;
            var x = h6BottomSection.offset().top;
            x = x - 400;

            if (y > x) {
                item.css("position", "sticky");
                item.css("bottom", "0");
                item.css("z-index", "-1");
            } else {
                item.css("position", "unset");
            }
        });
    }
    /*-- h6-bottom-section scripts end --*/

    /*-- footer area mouse move scripts start --*/
    var v7FooterArea = $(".v7-footer-area");
    if (v7FooterArea.length) {
        v7FooterArea.on("mousemove", function (e) {
            var x = e.screenX + "px";
            var y = e.screenY + "px";

            $(".footer-section-mash-gard").css({
                left: x,
                top: y,
            });
        });
    }
    /*-- footer area mouse move scripts end --*/

})(jQuery);