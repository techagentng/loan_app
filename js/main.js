/*
 *
 *   INQ - Responsive Admin Theme
 *   version 1.0
 *
 */

$(document).ready(function () {

    // Add body-small class if window less than 768px
    if ($(this).width() < 769) {
        $('body').addClass('body-small');
        $(".navbar-fixed-top").removeClass("show-menu-full");
        $(".navbar-fixed-top").addClass("show-menu-min");
        $(".navbar-static-side").removeClass("fixed-menu");
        $(".nano-content").slimScroll({destroy: true});
    } else {
        $('body').removeClass('body-small');
        $(".navbar-static-side").addClass("fixed-menu");
        // Initialize slimscroll for right sidebar
        $('.nano-content').slimScroll({
            height: '100%',
            railOpacity: 0.4,
            wheelStep: 10
        });
    }

    // Collapse inqbox function
    $('.collapse-link').click(function () {
        var inqbox = $(this).closest('div.inqbox');
        var button = $(this).find('i');
        var content = inqbox.find('div.inqbox-content');
        content.slideToggle(200);
        button.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        inqbox.toggleClass('').toggleClass('border-bottom');
        setTimeout(function () {
            inqbox.resize();
            inqbox.find('[id^=map-]').resize();
        }, 50);
    });

    // Close inqbox function
    $('.close-link').click(function () {
        var content = $(this).closest('div.inqbox');
        content.remove();
    });

    // Close menu in canvas mode
    $('.close-canvas-menu').click(function () {
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
    });

    if ($(".body-small").hasClass("mini-navbar"))
    {
        $(".navbar-fixed-top").addClass("show-menu-min");
    }
    else
    {
        $(".navbar-fixed-top").removeClass("show-menu-min");
    }

    // Minimalize menu
    $('.navbar-minimalize').click(function () {
        $("body").toggleClass("mini-navbar");

        if ($(".body-small").hasClass("mini-navbar"))
        {
            $(".navbar-fixed-top").addClass("show-menu-min");
        }
        else
        {
            $(".navbar-fixed-top").removeClass("show-menu-min");
        }

        PushMenu();
    });

    // Move modal to body
    // Fix Bootstrap backdrop issu with animation.css
    $('.modal').appendTo("body");

    // Full height of sidebar
    function fix_height() {
        var heightWithoutNavbar = $("body > #wrapper").height() - 61;
        $(".sidebard-panel").css("min-height", heightWithoutNavbar + "px");

        var navbarHeigh = $('nav.navbar-default').height();
        var wrapperHeigh = $('#page-wrapper').height();

        if (navbarHeigh > wrapperHeigh) {
            $('#page-wrapper').css("min-height", navbarHeigh + "px");
        }

        if (navbarHeigh < wrapperHeigh) {
            $('#page-wrapper').css("min-height", $(window).height() + "px");
        }

        if ($('body').hasClass('fixed-nav')) {
            $('#page-wrapper').css("min-height", $(window).height() - 60 + "px");
        }

    }

    fix_height();

    // Fixed Sidebar
    $(window).bind("load", function () {
        if ($("body").hasClass('fixed-sidebar')) {
            $('.nano-content').slimScroll({
                height: '100%',
                railOpacity: 0.9
            });
        }
    });

    // Move right sidebar top after scroll
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0 && !$('body').hasClass('fixed-nav')) {
            $('#right-sidebar').addClass('sidebar-top');
        } else {
            $('#right-sidebar').removeClass('sidebar-top');
        }
    });

    $(window).bind("load resize scroll", function () {
        if (!$("body").hasClass('body-small')) {
            fix_height();
        }
    });

    // Toggle Left Menu
    $('.nav-parent > a').on('click', function () {

        if ($(".navbar-static-side").width() === 70)
        {
            return false;
        }

        var gran = $(this).closest('.nav');
        var parent = $(this).parent();
        var sub = parent.find('> ul');

        if (sub.is(':visible')) {
            sub.slideUp(200);
            if (parent.hasClass('nav-active')) {
                parent.removeClass('nav-active');
            }
        } else {

            $(gran).find('.children').each(function () {
                $(this).slideUp();
            });

            sub.slideDown(200);
            if (!parent.hasClass('active')) {
                parent.addClass('nav-active');
            }
        }
        return false;

    });

    $(".nav-inq > li > a").hover(function () {
        var ele = $(this);
        if ($("body").hasClass("body-small"))
        {
            $(".nano").height($(window).height() - ($(".leftpanel-profile").outerHeight() + $(".logopanel").outerHeight()));
        }

        if ($(".navbar-static-side").width() === 70)
        {
            var top = ele.parent().position().top + 141;

            $("#hover-menu").css("top", top);
            if ($.trim($(this).siblings().html()) !== "")
            {
                var $content = "<span class='close' id='close-hover-menu' title='Close'>x&nbsp;</span><ul>" + $.trim($(this).siblings().html()) + "</ul>";
                $("#hover-menu").html($content);
            }
            else
            {
                $("#hover-menu").html("");
            }
        }
    });

    $(document).on("click", "#close-hover-menu", function () {
        $("#hover-menu").html("");
    });

    if ($("body").hasClass("body-small"))
    {
        $(".children").hide();
    }

    $(".nano").height($(window).height() - ($(".leftpanel-profile").outerHeight() + $(".logopanel").outerHeight()));

    $(window).resize(function () {
        $(".nano").height($(window).height() - ($(".leftpanel-profile").outerHeight() + $(".logopanel").outerHeight()));

        if ($(".navbar-static-side").width() < 220)
        {
            $(".navbar-fixed-top").removeClass("show-menu-full");
            $(".navbar-fixed-top").addClass("show-menu-min");
            $(".children").hide();
        }
        else
        {
            $(".navbar-fixed-top").addClass("show-menu-full");
            $(".navbar-fixed-top").removeClass("show-menu-min");
            $("#hover-menu").html("");
        }

        if ($(".body-small").hasClass("mini-navbar"))
        {
            $(".navbar-fixed-top").addClass("show-menu-min");
        }
        else
        {
            $(".navbar-fixed-top").removeClass("show-menu-min");
        }

    });

    $("a.navbar-minimalize").click(function () {
        if (!$("body").hasClass("body-small"))
        {
            toggle_menu();
        }
    });

    function toggle_menu()
    {
        if ($(".navbar-static-side").width() === 220)
        {
            $(".navbar-fixed-top").removeClass("show-menu-full");
            $(".navbar-fixed-top").addClass("show-menu-min");
            $(".children").hide();
        }
        else
        {
            $(".navbar-fixed-top").addClass("show-menu-full");
            $(".navbar-fixed-top").removeClass("show-menu-min");
            $("#hover-menu").html("");
        }

        $(".logo-element").addClass("animated fadeIn");
    }
});


// Minimalize menu when screen is less than 768px
$(window).bind("resize", function () {
    if ($(this).width() < 769) {
        $('body').addClass('body-small')
    } else {
        $('body').removeClass('body-small')
    }
});

function PushMenu() {
    if (!$('body').hasClass('mini-navbar') || $('body').hasClass('body-small')) {
        // Hide menu in order to smoothly turn on when maximize menu
        $('#side-menu').hide();
        // For smoothly turn on menu
        setTimeout(
                function () {
                    $('#side-menu').fadeIn(500);
                }, 100);
    } else if ($('body').hasClass('fixed-sidebar')) {
        $('#side-menu').hide();
        setTimeout(
                function () {
                    $('#side-menu').fadeIn(500);
                }, 300);
    } else {
        // Remove all inline style from jquery fadeIn function to reset menu state
        $('#side-menu').removeAttr('style');
    }
}