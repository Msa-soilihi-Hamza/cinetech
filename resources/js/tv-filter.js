document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const showsGrid = document.querySelector('.shows-grid');
    const desktopButtons = document.querySelectorAll('[data-genre]');
    
    function updateShows(genre) {
        const queryString = genre ? `genre=${genre}` : '';
        
        // Mettre à jour l'URL sans recharger la page
        window.history.pushState({}, '', `${window.location.pathname}${queryString ? '?' + queryString : ''}`);
        
        // Ajouter une classe pour l'animation de chargement
        if (showsGrid) {
            showsGrid.classList.add('opacity-50', 'transition-opacity');
        }
        
        fetch(`${window.location.pathname}?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            if (showsGrid) {
                showsGrid.innerHTML = html;
                showsGrid.classList.remove('opacity-50');
                
                // Réinitialiser AOS pour les nouveaux éléments
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des séries:', error);
            if (showsGrid) {
                showsGrid.classList.remove('opacity-50');
            }
        });
    }
    
    // Gestionnaire pour le formulaire mobile
    if (filterForm) {
        const select = filterForm.querySelector('select');
        if (select) {
            select.addEventListener('change', function(e) {
                e.preventDefault();
                updateShows(this.value);
            });
        }
    }
    
    // Gestionnaire pour les boutons desktop
    desktopButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const genre = this.getAttribute('data-genre');
            updateShows(genre);
            
            // Mettre à jour l'état actif des boutons
            desktopButtons.forEach(btn => {
                btn.classList.remove('bg-purple-600', 'text-white');
                btn.classList.add('bg-gray-800', 'text-gray-300');
            });
            this.classList.add('bg-purple-600', 'text-white');
            this.classList.remove('bg-gray-800', 'text-gray-300');
        });
    });
}); 