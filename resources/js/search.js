document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let timeoutId = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        timeoutId = setTimeout(() => {
            fetch(`/search/autocomplete?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'flex items-center p-2 hover:bg-gray-700 cursor-pointer';
                            
                            const img = item.poster_path 
                                ? `<img src="${item.poster_path}" class="w-10 h-15 object-cover mr-3">` 
                                : '<div class="w-10 h-15 bg-gray-600 mr-3 flex items-center justify-center"><span class="text-xs text-gray-400">No image</span></div>';
                            
                            div.innerHTML = `
                                ${img}
                                <div class="flex-1">
                                    <div class="text-white">${item.title}</div>
                                    <div class="text-sm text-gray-400">
                                        ${item.media_type === 'movie' ? 'Film' : 'Série'} ${item.year ? `(${item.year})` : ''}
                                    </div>
                                </div>
                            `;
                            
                            div.addEventListener('click', () => {
                                window.location.href = `/${item.media_type === 'movie' ? 'movie' : 'tv'}/${item.id}`;
                            });
                            
                            searchResults.appendChild(div);
                        });
                        
                        searchResults.classList.remove('hidden');
                    } else {
                        searchResults.classList.add('hidden');
                    }
                });
        }, 300);
    });

    // Cacher les résultats quand on clique en dehors
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
}); 