document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[type="text"]');
    const startupCards = document.querySelectorAll('.col-md-4.mb-4');
    const categoryLinks = document.querySelectorAll('.sidebar ul li a');

    // Search functionality
    searchInput?.addEventListener('keyup', function(e) {
        const searchText = e.target.value.toLowerCase();
        
        startupCards.forEach(card => {
            const title = card.querySelector('.card-title')?.textContent.toLowerCase();
            const description = card.querySelector('.card-text')?.textContent.toLowerCase();
            const category = card.querySelector('.badge')?.textContent.toLowerCase();
            
            if (title?.includes(searchText) || description?.includes(searchText) || category?.includes(searchText)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Category filter
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const selectedCategory = this.textContent.toLowerCase();
            
            startupCards.forEach(card => {
                const cardCategory = card.querySelector('.badge')?.textContent.toLowerCase();
                card.style.display = (selectedCategory === 'autres' || cardCategory === selectedCategory) ? '' : 'none';
            });

            // Update active state
            categoryLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
