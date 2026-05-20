// Mobile menu toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target) && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
        }
    });
}

// Mobile filter toggle
const filterBtn = document.getElementById('mobile-filter-btn');
const filterPanel = document.getElementById('filters-panel');
const filterArrow = document.getElementById('filter-arrow');

if (filterBtn && filterPanel && filterArrow) {
    filterBtn.addEventListener('click', () => {
        filterPanel.classList.toggle('open');
        filterArrow.style.transform = filterPanel.classList.contains('open') ? 'rotate(180deg)' : 'rotate(0deg)';
    });
}
