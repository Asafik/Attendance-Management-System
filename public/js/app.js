$(document).ready(function() {
    // ===== DARK MODE DEFAULT (sesuai CSS terbaru) =====
    const savedMode = localStorage.getItem('darkMode');

    if (savedMode === 'enabled') {
        // User memilih dark mode
        $('body').removeClass('light-mode');
        $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
    } else if (savedMode === 'disabled') {
        // User memilih light mode
        $('body').addClass('light-mode');
        $('#darkModeToggle i').removeClass('fa-sun').addClass('fa-moon');
    } else {
        // DEFAULT DARK MODE
        $('body').removeClass('light-mode');
        $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
        localStorage.setItem('darkMode', 'enabled');
    }

    // ===== SIDEBAR TOGGLE =====
    $('#toggleSidebar').click(function() {
        if ($(window).width() <= 992) {
            $('#sidebar').toggleClass('active');
            $('#sidebarOverlay').toggleClass('active');
        } else {
            $('#sidebar').toggleClass('collapsed');
            $('#mainContent').toggleClass('expanded');
        }
    });

    $('#sidebarOverlay').click(function() {
        $('#sidebar').removeClass('active');
        $('#sidebarOverlay').removeClass('active');
    });

    // ===== SUBMENU TOGGLE =====
    $('.has-submenu > .nav-link').click(function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });

    // Keep submenu open if any child is active
    if ($('.submenu-link.active').length > 0) {
        $('.nav-item.has-submenu').addClass('open');
    }

    // ===== USER PROFILE DROPDOWN =====
    $('#userProfile').click(function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#userProfile').length) {
            $('#userProfile').removeClass('active');
        }
    });

    // ===== DARK MODE TOGGLE =====
    $('#darkModeToggle').click(function() {
        $('body').toggleClass('light-mode');
        var icon = $(this).find('i');

        if ($('body').hasClass('light-mode')) {
            // Light mode aktif
            icon.removeClass('fa-sun').addClass('fa-moon');
            localStorage.setItem('darkMode', 'disabled');
        } else {
            // Dark mode aktif
            icon.removeClass('fa-moon').addClass('fa-sun');
            localStorage.setItem('darkMode', 'enabled');
        }
    });

    // ===== RESIZE HANDLER =====
    $(window).resize(function() {
        if ($(window).width() > 992) {
            $('#sidebar').removeClass('active');
            $('#sidebarOverlay').removeClass('active');
        }
    });
});
