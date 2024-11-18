document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.favorite-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const button = form.querySelector('button');
            
            fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-red-500');
                    
                    setTimeout(() => {
                        window.location.href = '/favorites';
                    }, 1500);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    });
}); 