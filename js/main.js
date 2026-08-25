(function() {
    'use strict';
    
    var toggle = document.querySelector('.mobile-menu-toggle');
    var nav = document.querySelector('.header-nav');
    
    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            var isOpen = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!isOpen));
            nav.classList.toggle('nav-open');
            toggle.classList.toggle('active');
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                toggle.setAttribute('aria-expanded', 'false');
                nav.classList.remove('nav-open');
                toggle.classList.remove('active');
            }
        });
        
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                toggle.setAttribute('aria-expanded', 'false');
                nav.classList.remove('nav-open');
                toggle.classList.remove('active');
            }
        });
    }
})();
