(function($, window, undefined) {

    // /* ====== SHARED VARS  - jQuery ====== */
    // These depend on jQuery
    var $window = $(window),
        windowHeight = $window.height(),
        windowWidth = $window.width();

    /**
     * Detect browser size and remember it in global variables
     */

    function browserSize() {
        wh = $window.height();
        ww = $window.width();
        dh = $(document).height();
        ar = ww / wh;
    }


    /**
     * Detect what platform are we on (browser, mobile, etc)
     */

    function platformDetect() {
        $.support.touch = 'ontouchend' in document;
        var navUA = navigator.userAgent.toLowerCase(),
            navPlat = navigator.platform.toLowerCase();

        var isiPhone = navPlat.indexOf("iphone"),
            isiPod = navPlat.indexOf("ipod"),
            isAndroidPhone = navPlat.indexOf("android"),
            safari = (navUA.indexOf('safari') != -1 && navUA.indexOf('chrome') == -1) ? true : false,
            svgSupport = (window.SVGAngle) ? true : false,
            svgSupportAlt = (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1")) ? true : false,
            ff3x = (/gecko/i.test(navUA) && /rv:1.9/i.test(navUA)) ? true : false;

        ieMobile = navigator.userAgent.match(/Windows Phone/i) ? true : false;
        phone = (isiPhone > -1 || isiPod > -1 || isAndroidPhone > -1) ? true : false;
        touch = $.support.touch ? true : false;

        if (touch) $html.addClass('touch');

        if (ieMobile) $html.addClass('is--winmob');
        if (is_android) $html.addClass('is--ancient-android');

        if (safari) $body.addClass('safari');
        if (phone) $body.addClass('phone');
    }

    // /* ====== SHARED VARS ====== */
    // These do not depend on jQuery
    var phone, touch, wh, ww, dh, ar, fonts;

    var ua = navigator.userAgent;
    var winLoc = window.location.toString();

    var is_webkit = ua.match(/webkit/i);
    var is_firefox = ua.match(/gecko/i);
    var is_newer_ie = ua.match(/msie (9|([1-9][0-9]))/i);
    var is_older_ie = ua.match(/msie/i) && !is_newer_ie;
    var is_ancient_ie = ua.match(/msie 6/i);
    var is_mobile = ua.match(/mobile/i);
    var is_OSX = (ua.match(/(iPad|iPhone|iPod|Macintosh)/g) ? true : false);

    var nua = navigator.userAgent;
    var is_android = ((nua.indexOf('Mozilla/5.0') !== -1 && nua.indexOf('Android ') !== -1 && nua.indexOf('AppleWebKit') !== -1) && nua.indexOf('Chrome') === -1);

    var useTransform = true;
    var use2DTransform = (ua.match(/msie 9/i) || winLoc.match(/transform\=2d/i));
    var transform;

    var $html = $('html');
    var $body = $('body');

    // setting up transform prefixes
    var prefixes = {
        webkit: 'webkitTransform',
        firefox: 'MozTransform',
        ie: 'msTransform',
        w3c: 'transform'
    };

    if (useTransform) {
        if (is_webkit) {
            transform = prefixes.webkit;
        } else if (is_firefox) {
            transform = prefixes.firefox;
        } else if (is_newer_ie) {
            transform = prefixes.ie;
        }
    }
    // /* ====== Masonry Logic ====== */
    (function() {

        var $container = $('.archive__grid'),
            $blocks = $container.children().addClass('post--animated  post--loaded'),
            slices = $blocks.first().children().length;

        // initialize masonry after the images have loaded
        $container.imagesLoaded(function() {

            // prepare hover animations
            if (!$html.hasClass('touch'))
                $blocks.addHoverAnimation();

            // initialize masonry
            $container.masonry({
                isAnimated: false,
                itemSelector: '.grid__item',
                hiddenStyle: {
                    opacity: 0
                }
            });

            /**
             * function used to display cards with a simple fade in transition
             */

            function showBlocks($blocks) {

                // use different delays for each card to stagger them
                $blocks.each(function(i, obj) {

                    var $block = $(obj),
                        duration = 200 + slices * 0.1;

                    $block.velocity({
                        translateY: 40
                    }, {
                        duration: 0
                    });
                    setTimeout(function() {
                        $block.velocity({
                            translateY: 0
                        }, {
                            easing: "easeOutQuad",
                            duration: 100
                        });

                        $block.children().each(function(j, child) {
                            var $child = $(child),
                                timeout = (j + 1) * 100;

                            $child.velocity({
                                opacity: 0,
                                translateY: 40
                            }, {
                                duration: 0
                            });

                            $child.velocity({
                                opacity: 1,
                                translateY: 0
                            }, {
                                duration: 300,
                                delay: timeout
                            });
                        });

                        setTimeout(function() {
                            $block.toggleClass('sticky--bg', $block.hasClass('sticky'));
                            $block.addClass('post--visible');
                        }, 300);

                    }, i * 200);
                });
            }

            // animate cards in
            showBlocks($blocks);

            // update the masonry layout on window.resize
            $(window).smartresize(function() {
                $container.masonry('layout');
            });

            $(window).load(function() {
                $container.masonry('layout');
            });

            // handle behavior for infinite scroll
            $(document.body).on('post-load', function() {

                // figure out which are the new loaded posts
                var $newBlocks = $('.archive__grid').children().not('.post--loaded').addClass('post--loaded');

                // when images have loaded take care of the layout, prepare hover animations, and animate cards in
                $newBlocks.imagesLoaded(function() {
                    $container.masonry('appended', $newBlocks, true).masonry('layout');
                    if (!$html.hasClass('touch'))
                        $newBlocks.addHoverAnimation();
                    showBlocks($newBlocks);
                });
            });

        });

    })();

    // /* ====== Navigation Logic ====== */
    var $nav = $('.nav--main'),
        $navTrigger = $('.navigation__trigger'),
        $navContainer = $('.main-navigation'),
        navTop = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().top : 0,
        navLeft = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().left : 0,
        navWidth = $nav.outerWidth(),
        containerWidth = $navContainer.outerWidth(),
        navHeight = $nav.outerHeight(),
        isOpen = false,
        pageTop = $('#page').offset().top,
        sticked = false;

    /**
     * bind toggling the navigation drawer to click and touchstart
     *in order to get rid of the 300ms delay on touch devices we use the touchstart event
     */
    var triggerEvents = 'click touchstart';
    if (is_android) triggerEvents = 'click';

    // Set the Navigation Container Height
    $navContainer.height(navHeight);

    $navTrigger.on(triggerEvents, function(e) {
        // but we still have to prevent the default behavior of the touchstart event
        // because this way we're making sure the click event won't fire anymore
        e.preventDefault();
        e.stopPropagation();

        isOpen = !isOpen;
        $('body').toggleClass('nav--is-open');

        var offset;

        navWidth = $nav.outerWidth();

        if ($('body').hasClass('rtl')) {
            offset = -1 * navWidth;
        } else {
            offset = navWidth;
        }

        if (!is_android) {
            if (!isOpen) {

                $([$nav, $navTrigger]).each(function(i, obj) {
                    $(obj).velocity({
                        translateX: 0,
                        translateZ: 0.01
                    }, {
                        duration: 400,
                        easing: "ease",
                        begin: function() {
                            $navContainer.removeClass('shadow');
                        }
                    });
                });

            } else {

                $([$nav, $navTrigger]).each(function(i, obj) {
                    $(obj).velocity({
                        translateX: offset,
                        translateZ: 0.01
                    }, {
                        easing: "ease",
                        duration: 400,
                        complete: function() {
                            $navContainer.addClass('shadow');
                        }
                    });
                });
            }
        }
    });

    var HandleParentMenuItems = (function() {
        // Handle parent menu items on tablet in landscape mode;
        // use case: normal, horizontal menu, touch events,
        // sub menus are not visible.
        function handleParentMenuItems() {
            // Make sure there are no open menu items
            $('[class*="has-children"]').removeClass('hover');

            $('[class*="has-children"] > a').each(function() {
                // Add a class so we know the items to handle
                $(this).addClass('prevent-one').attr('hasbeenclicked', false);
            });

            $('a.prevent-one').on('click', function(e) {
                if ($(this).attr('hasbeenclicked') == "false") {
                    e.preventDefault();
                    e.stopPropagation();

                    $(this).attr('hasbeenclicked', true);

                    // Open the sub menu of this parent item
                    $(this).parent().addClass('hover')
                        // When a parent menu item is activated,
                        // close other menu items on the same level
                        .siblings().removeClass('hover').find('> a.prevent-one').attr('hasbeenclicked', false);
                }
            });
        }

        // Restore the original behaviour when in portrait mode;
        // use case: vertical menu, all menu items are visible.
        function unHandleParentMenuItems() {
            $('a.prevent-one').each(function() {
                // Unbind te click handler
                $(this).unbind();
            });
        }

        // When a sub menu is open, close it by a touch on
        // any other part of the viewport than navigation.
        // use case: normal, horizontal menu, touch events,
        // sub menus are not visible.
        function bindOuterNavClick() {
            $('body').on('touchstart', function(e) {
                var container = $('.nav--main');

                if (!container.is(e.target) // if the target of the click isn't the container...
                    &&
                    container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    $('[class*="has-children"]').removeClass('hover');
                    $('a.prevent-one').attr('hasbeenclicked', false);
                }
            });
        }

        return {
            handle: handleParentMenuItems,
            unHandle: unHandleParentMenuItems,
            bindOuterNavClick: bindOuterNavClick
        }
    }());

    /**
     * because we hard-code properties on the navigation bar when we stick it at the top of the window
     * we need to update some properties on window resize
     */

    function refreshNavigation() {
        navTop = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().top : 0;
        navLeft = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().left : 0;
        containerWidth = $navContainer.outerWidth();
        navWidth = $nav.outerWidth();
        navHeight = $nav.outerHeight();
        pageTop = $('#page').offset().top;

        $navContainer.height(navHeight);

        if (sticked) {
            $nav.velocity({
                width: containerWidth
            }, {
                easing: "easeOutCubic",
                duration: 200
            });
        }

        if (isOpen) {
            $([$nav, $navTrigger]).each(function(i, obj) {
                $(obj).velocity({
                    translateX: navWidth
                }, {
                    easing: "easeOutQuint",
                    duration: 200
                });
            });
        }

        navWidth = containerWidth;
    }

    /**
     * cardHover jQuery plugin
     *
     * we need to create a jQuery plugin so we can easily create the hover animations on the archive
     * both an window.load and on jetpack's infinite scroll 'post-load'
     */
    $.fn.addHoverAnimation = function() {

        return this.each(function(i, obj) {

            var $obj = $(obj),
                $handler = $obj.find('.hover__handler'),
                $hover = $obj.find('.hover');

            // if we don't have have elements that need to be animated return
            if (!$hover.length) {
                return;
            }

            var $letter = $hover.find('.hover__letter'),
                letterWidth = $letter.outerWidth,
                letterHeight = $letter.outerHeight;

            $hover.find('.hover__bg').velocity({
                opacity: 0
            }, {
                duration: 0
            });
            $hover.find('.hover__line').velocity({
                height: 0
            }, {
                duration: 0
            });
            $hover.find('.hover__more').velocity({
                opacity: 0
            }, {
                duration: 0
            });
            $hover.find('.hover__letter').velocity({
                opacity: 0,
                translateX: '-50%',
                translateY: '-50%',
                scaleX: 1.25,
                scaleY: 1.25
            }, {
                duration: 0
            });

            // bind the tweens we created above to mouse events accordingly, through hoverIntent to avoid flickering
            if ($handler.length) {
                $handler.hoverIntent({
                    over: animateHoverIn,
                    out: animateHoverOut,
                    timeout: 100,
                    interval: 50
                });
            }

            function animateHoverIn() {

                $hover.find('.hover__bg').velocity("reverse", {
                    easing: "easeOutQuart",
                    duration: 500
                });
                $hover.find('.hover__line').velocity("reverse", {
                    easing: "easeOutQuart",
                    duration: 350,
                    delay: 150
                });
                $hover.find('.hover__more').velocity("reverse", {
                    easing: "easeOutQuart",
                    duration: 350,
                    delay: 150
                });
                $hover.find('.hover__letter').velocity({
                    scaleX: 1,
                    scaleY: 1,
                    opacity: 0.2,
                    translateX: '-50%',
                    translateY: '-50%'
                }, {
                    easing: "easeOutQuart",
                    duration: 350,
                    delay: 150
                });

            }

            function animateHoverOut() {

                var letterHeight = $hover.find('.hover__letter-mask').outerHeight();

                $hover.find('.hover__letter').velocity({
                    opacity: 0,
                    translateX: '-50%',
                    translateY: '-50%',
                    scaleX: 1.25,
                    scaleY: 1.25
                }, {
                    easing: "easeOutQuart",
                    duration: 350,
                    delay: 0
                });
                $hover.find('.hover__more').velocity("reverse", {
                    duration: 350,
                    delay: 0
                });
                $hover.find('.hover__line').velocity("reverse", {
                    duration: 350,
                    delay: 0
                });
                $hover.find('.hover__bg').velocity("reverse", {
                    duration: 350,
                    delay: 0
                });

            }

        });
    };

    // /* ====== Search Overlay Logic ====== */
    (function() {

        var isOpen = false,
            $overlay = $('.overlay--search');

        // update overlay position (if it's open) on window.resize
        $(window).on('smartresize', function() {

            windowWidth = $(window).outerWidth();

            if (isOpen) {
                $overlay.velocity({
                    translateX: -1 * windowWidth
                }, {
                    duration: 200,
                    easing: "easeInCubic"
                });
            }

        });

        /**
         * dismiss overlay
         */

        function closeOverlay() {

            if (!isOpen) {
                return;
            }

            var offset;

            if ($('body').hasClass('rtl')) {
                offset = windowWidth
            } else {
                offset = -1 * windowWidth
            }

            // we don't need a timeline for this animations so we'll use a simple tween between two states
            $overlay.velocity({
                translateX: offset
            }, {
                duration: 0
            });
            $overlay.velocity({
                translateX: 0
            }, {
                duration: 300,
                easing: "easeInCubic"
            });

            // remove focus from the search field
            $overlay.find('input').blur();

            isOpen = false;
        }

        function escOverlay(e) {
            if (e.keyCode == 27) {
                closeOverlay();
            }
        }

        // create animation and run it on
        $('.nav__item--search').on('click touchstart', function(e) {
            // prevent default behavior and stop propagation
            e.preventDefault();
            e.stopPropagation();

            // if through some kind of sorcery the navigation drawer is already open return
            if (isOpen) {
                return;
            }

            var offset;

            if ($('body').hasClass('rtl')) {
                offset = windowWidth
            } else {
                offset = -1 * windowWidth
            }

            // automatically focus the search field so the user can type right away
            $overlay.find('input').focus();

            $overlay.velocity({
                translateX: 0
            }, {
                duration: 0
            }).velocity({
                translateX: offset
            }, {
                duration: 300,
                easing: "easeOut",
                queue: false
            });

            $('.search-form').velocity({
                translateX: 300,
                opacity: 0
            }, {
                duration: 0
            }).velocity({
                opacity: 1
            }, {
                duration: 200,
                easing: "easeOutQuad",
                delay: 200,
                queue: false
            }).velocity({
                translateX: 0
            }, {
                duration: 400,
                easeing: [0.175, 0.885, 0.320, 1.275],
                delay: 50,
                queue: false
            });

            $('.overlay__wrapper > p').velocity({
                translateX: 200,
                opacity: 0
            }, {
                duration: 0
            }).velocity({
                opacity: 1
            }, {
                duration: 400,
                easing: "easeOutQuad",
                delay: 350,
                queue: false
            }).velocity({
                translateX: 0
            }, {
                duration: 400,
                easing: [0.175, 0.885, 0.320, 1.275],
                delay: 250,
                queue: false
            });

            // bind overlay dismissal to escape key
            $(document).on('keyup', escOverlay);

            isOpen = true;
        });

        // create function to hide the search overlay and bind it to the click event
        $('.overlay__close').on('click touchstart', function(e) {

            e.preventDefault();
            e.stopPropagation();

            closeOverlay();

            // unbind overlay dismissal from escape key
            $(document).off('keyup', escOverlay);

        });

    })();

    // /* ====== Smart Resize Logic ====== */
    // It's best to debounce the resize event to a void performance hiccups
    (function($, sr) {

        /**
         * debouncing function from John Hann
         * http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
         */
        var debounce = function(func, threshold, execAsap) {
            var timeout;

            return function debounced() {
                var obj = this,
                    args = arguments;

                function delayed() {
                    if (!execAsap) func.apply(obj, args);
                    timeout = null;
                }

                if (timeout) clearTimeout(timeout);
                else if (execAsap) func.apply(obj, args);

                timeout = setTimeout(delayed, threshold || 200);
            };
        }
        // smartresize
        jQuery.fn[sr] = function(fn) {
            return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
        };

    })(jQuery, 'smartresize');
    var latestKnownScrollY = window.scrollY,
        ticking = false;

    function requestTick() {
        "use strict";

        if (!ticking) {
            requestAnimationFrame(update);
        }
        ticking = true;
    }

    function update() {
        "use strict";

        ticking = false;

    }

    /* ====== INTERNAL FUNCTIONS END ====== */


    /* ====== ONE TIME INIT ====== */

    function init() {

        // /* GLOBAL VARS */
        touch = false;

        //  GET BROWSER DIMENSIONS
        browserSize();

        // /* DETECT PLATFORM */
        platformDetect();
    }


    /* --- GLOBAL EVENT HANDLERS --- */


    /* ====== ON DOCUMENT READY ====== */

    $(document).ready(function() {
        init();
    });


    /* ====== ON WINDOW LOAD ====== */

    $window.load(function() {

        if (!$html.hasClass('touch')) {
            var $nav = $('.nav--main').addClass('hover-intent');

            $('.nav--main li').hoverIntent({
                over: showSubMenu,
                out: hideSubMenu,
                timeout: 300
            });

        } else if ($html.hasClass('touch') && windowWidth > 1000) {
            HandleParentMenuItems.handle();
            HandleParentMenuItems.bindOuterNavClick();
        }

        function showSubMenu() {
            $(this).addClass('hover');
        }

        function hideSubMenu() {
            $(this).removeClass('hover');
        }

    });

    /* ====== ON RESIZE ====== */

    $(window).smartresize(function() {
        if (!is_android) {
            refreshNavigation();
        }
        windowWidth = $(window).outerWidth();
        windowHeight = $(window).outerHeight();

        if ($html.hasClass('touch')) {
            if (windowWidth >= 1000) {
                // Handle parent menu items
                HandleParentMenuItems.handle();
            } else if (windowWidth < 1000) {
                // Remove handlers
                HandleParentMenuItems.unHandle();
            }
        }
    });

    /* ====== ON SCROLL ====== */

    $window.on('scroll', function(e) {
        "use strict";

        latestKnownScrollY = window.scrollY;
        requestTick();
    });
    // /* ====== HELPER FUNCTIONS ====== */
    /**
     * function similar to PHP's empty function
     */

    function empty(data) {
        if (typeof(data) == 'number' || typeof(data) == 'boolean') {
            return false;
        }
        if (typeof(data) == 'undefined' || data === null) {
            return true;
        }
        if (typeof(data.length) != 'undefined') {
            return data.length === 0;
        }
        var count = 0;
        for (var i in data) {
            // if(data.hasOwnProperty(i))
            //
            // This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
            // http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
            //
            // for hosts objects we do this
            if (Object.prototype.hasOwnProperty.call(data, i)) {
                count++;
            }
        }
        return count === 0;
    }

    /**
     * function to add/modify a GET parameter
     */

    function setQueryParameter(uri, key, value) {
        var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
        separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }

    /**
     * requestAnimationFrame polyfill by Erik Möller.
     * Fixes from Paul Irish, Tino Zijdel, Andrew Mao, Klemen Slavič, Darius Bacon
     *
     * MIT license
     */

    if (!Date.now)
        Date.now = function() {
            return new Date().getTime();
        };

    (function() {
        'use strict';

        var vendors = ['webkit', 'moz'];
        for (var i = 0; i < vendors.length && !window.requestAnimationFrame; ++i) {
            var vp = vendors[i];
            window.requestAnimationFrame = window[vp + 'RequestAnimationFrame'];
            window.cancelAnimationFrame = (window[vp + 'CancelAnimationFrame'] ||
                window[vp + 'CancelRequestAnimationFrame']);
        }
        if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) // iOS6 is buggy
            ||
            !window.requestAnimationFrame || !window.cancelAnimationFrame) {
            var lastTime = 0;
            window.requestAnimationFrame = function(callback) {
                var now = Date.now();
                var nextTime = Math.max(lastTime + 16, now);
                return setTimeout(function() {
                        callback(lastTime = nextTime);
                    },
                    nextTime - now);
            };
            window.cancelAnimationFrame = clearTimeout;
        }
    }());

})(jQuery, window);