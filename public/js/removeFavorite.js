function removeFavorite(event, tmdbId) {
    event.preventDefault();
    
    const form = event.target;
    const card = document.getElementById(`favorite-${tmdbId}`);
    
    fetch(form.action, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            tmdb_id: tmdbId,
            type: form.querySelector('input[name="type"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();
                
                const countElement = document.querySelector('.text-gray-400');
                if (countElement) {
                    const currentCount = parseInt(countElement.textContent);
                    if (currentCount > 1) {
                        countElement.textContent = `${currentCount - 1} favoris`;
                    } else {
                        location.reload();
                    }
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
} 