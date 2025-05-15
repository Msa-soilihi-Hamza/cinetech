/**
 * Gestion des commentaires et réponses
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation du script de commentaires');
    
    // Attacher les écouteurs d'événements aux boutons existants
    setupCommentEventListeners();
    
    // Afficher des informations de débogage
    debugCommentElements();
});

/**
 * Configure tous les écouteurs d'événements pour les boutons de commentaires
 */
function setupCommentEventListeners() {
    // Boutons pour modifier les commentaires
    const editButtons = document.querySelectorAll('[data-edit-comment]');
    console.log('Boutons d\'édition trouvés:', editButtons.length);
    
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.getAttribute('data-edit-comment');
            console.log('Clic sur le bouton d\'édition pour:', commentId);
            toggleEditForm(commentId);
        });
    });

    // Boutons pour les réponses
    const replyButtons = document.querySelectorAll('[data-reply-toggle]');
    console.log('Boutons de réponse trouvés:', replyButtons.length);
    
    replyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const replyFormId = this.getAttribute('data-reply-toggle');
            console.log('Clic sur le bouton de réponse pour:', replyFormId);
            toggleReplyForm(replyFormId);
        });
    });

    // Formulaires de suppression
    const deleteForms = document.querySelectorAll('form[action*="comments/"]');
    console.log('Formulaires de suppression trouvés:', deleteForms.length);
    
    deleteForms.forEach(form => {
        if (form.method === 'post' && form.innerHTML.includes('DELETE')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Tentative de suppression du commentaire');
                if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible.')) {
                    this.submit();
                }
            });
        }
    });
}

/**
 * Affiche des informations de débogage sur les éléments de commentaire
 */
function debugCommentElements() {
    // Vérifier les formulaires d'édition
    const editForms = document.querySelectorAll('.comment-edit-form');
    console.log('Formulaires d\'édition trouvés:', editForms.length);
    
    // Vérifier les contenus de commentaire
    const contentElements = document.querySelectorAll('.comment-content');
    console.log('Éléments de contenu trouvés:', contentElements.length);
    
    // Vérifier les éléments de commentaire
    const commentElements = document.querySelectorAll('[id^="comment-"]');
    console.log('Éléments de commentaire trouvés:', commentElements.length);
    commentElements.forEach(element => {
        console.log('ID de commentaire:', element.id);
    });
}

/**
 * Bascule l'affichage du formulaire d'édition
 * Cette fonction peut être appelée soit par un gestionnaire d'événements JavaScript,
 * soit directement par un attribut onclick dans le HTML
 */
function toggleEditForm(id) {
    console.log('toggleEditForm appelé avec id:', id);
    
    const commentElement = document.getElementById(id);
    if (!commentElement) {
        console.error('Élément de commentaire non trouvé:', id);
        return;
    }
    
    const contentElement = commentElement.querySelector('.comment-content');
    const formElement = commentElement.querySelector('.comment-edit-form');
    
    console.log('Éléments trouvés:', {
        commentElement: !!commentElement,
        contentElement: !!contentElement,
        formElement: !!formElement
    });
    
    if (contentElement && formElement) {
        contentElement.classList.toggle('hidden');
        formElement.classList.toggle('hidden');
        console.log('Basculement effectué');
    } else {
        console.error('Éléments de contenu ou de formulaire non trouvés dans:', id);
    }
}

/**
 * Bascule l'affichage du formulaire de réponse
 * Cette fonction peut être appelée soit par un gestionnaire d'événements JavaScript,
 * soit directement par un attribut onclick dans le HTML
 */
function toggleReplyForm(id) {
    console.log('toggleReplyForm appelé avec id:', id);
    
    const replyForm = document.getElementById(id);
    if (replyForm) {
        replyForm.classList.toggle('hidden');
        console.log('Basculement du formulaire de réponse effectué');
    } else {
        console.error('Formulaire de réponse non trouvé:', id);
    }
} 