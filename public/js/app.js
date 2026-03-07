$(document).ready(function() {
    // ===== DARK MODE DEFAULT (TAMBAHKAN INI PALING ATAS) =====
    const savedMode = localStorage.getItem('darkMode');

    if (savedMode === 'enabled') {
        $('body').addClass('dark-mode');
        $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
    } else if (savedMode === 'disabled') {
        $('body').removeClass('dark-mode');
        $('#darkModeToggle i').removeClass('fa-sun').addClass('fa-moon');
    } else {
        // DEFAULT DARK MODE
        $('body').addClass('dark-mode');
        $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
        localStorage.setItem('darkMode', 'enabled');
    }

    // Sidebar toggle
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

    // Toggle submenu
    $('.has-submenu > .nav-link').click(function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('open');
    });

    // Toggle user profile dropdown
    $('#userProfile').click(function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
    });

    $(document).click(function(e) {
        if (!$(e.target).closest('#userProfile').length) {
            $('#userProfile').removeClass('active');
        }
    });

    // Dark mode toggle
    $('#darkModeToggle').click(function() {
        $('body').toggleClass('dark-mode');
        var icon = $(this).find('i');

        if ($('body').hasClass('dark-mode')) {
            icon.removeClass('fa-moon').addClass('fa-sun');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            icon.removeClass('fa-sun').addClass('fa-moon');
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    // HAPUS BAGIAN INI (karena sudah di atas)
    // if (localStorage.getItem('darkMode') === 'enabled') {
    //     $('body').addClass('dark-mode');
    //     $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
    // }
});
