document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour mettre à jour l'apparence du bouton favori
    function updateFavoriteButton(form, isFavorite) {
        const button = form.querySelector('button');
        if (isFavorite) {
            button.classList.remove('text-gray-400');
            button.classList.add('text-red-500');
        } else {
            button.classList.remove('text-red-500');
            button.classList.add('text-gray-400');
        }
    }

    // Gestion des formulaires de favoris
    document.querySelectorAll('.favorite-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tmdb_id: form.querySelector('input[name="tmdb_id"]').value,
                        type: form.querySelector('input[name="type"]').value
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        // Mettre à jour l'apparence du bouton
                        const isRemoving = form.method === 'DELETE';
                        updateFavoriteButton(form, !isRemoving);
                        
                        // Afficher un message de succès temporaire
                        const message = isRemoving ? 'Retiré des favoris' : 'Ajouté aux favoris';
                        showNotification(message);
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        });
    });

    // Fonction pour afficher une notification
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}); 