// Gestionnaire pour les formulaires d'événements
document.addEventListener('DOMContentLoaded', function() {
    
    // Validation du formulaire de création d'événement
    const eventForm = document.getElementById('eventForm');
    if (eventForm) {
        eventForm.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const date = document.getElementById('date_event').value;
            const location = document.getElementById('location').value.trim();
            
            let errors = [];
            
            if (title.length < 3) {
                errors.push('Le titre doit contenir au moins 3 caractères');
            }
            
            if (description.length < 10) {
                errors.push('La description doit contenir au moins 10 caractères');
            }
            
            if (!date) {
                errors.push('La date est requise');
            } else {
                const selectedDate = new Date(date);
                const now = new Date();
                if (selectedDate < now) {
                    errors.push('La date ne peut pas être dans le passé');
                }
            }
            
            if (location.length < 2) {
                errors.push('Le lieu est requis');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Erreurs :\n- ' + errors.join('\n- '));
            }
        });
    }
    
    // Gestionnaire pour l'ajout d'invités
    const addGuestBtn = document.getElementById('addGuest');
    const guestList = document.getElementById('guestList');
    
    if (addGuestBtn && guestList) {
        let guestCount = 0;
        
        addGuestBtn.addEventListener('click', function() {
            guestCount++;
            
            const guestItem = document.createElement('div');
            guestItem.className = 'invitation-item';
            guestItem.innerHTML = `
                <div class="guest-info">
                    <input type="text" name="guests[${guestCount}][name]" 
                           placeholder="Nom de l'invité" class="form-control">
                    <input type="email" name="guests[${guestCount}][email]" 
                           placeholder="Email" class="form-control" required>
                </div>
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
            `;
            
            guestList.appendChild(guestItem);
        });
    }
    
    // Filtres pour la liste des événements
    const filterStatus = document.getElementById('filterStatus');
    const filterDate = document.getElementById('filterDate');
    const eventCards = document.querySelectorAll('.event-card');
    
    function filterEvents() {
        const status = filterStatus ? filterStatus.value : 'all';
        const date = filterDate ? filterDate.value : '';
        
        eventCards.forEach(card => {
            let show = true;
            
            if (status !== 'all') {
                const cardStatus = card.dataset.status;
                if (cardStatus !== status) show = false;
            }
            
            if (date && show) {
                const cardDate = card.dataset.date;
                if (cardDate !== date) show = false;
            }
            
            card.style.display = show ? 'block' : 'none';
        });
    }
    
    if (filterStatus) filterStatus.addEventListener('change', filterEvents);
    if (filterDate) filterDate.addEventListener('change', filterEvents);
    
    // Aperçu de la date dans le formulaire
    const dateInput = document.getElementById('date_event');
    const datePreview = document.getElementById('datePreview');
    
    if (dateInput && datePreview) {
        dateInput.addEventListener('change', function() {
            const date = new Date(this.value);
            if (!isNaN(date)) {
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                datePreview.textContent = date.toLocaleDateString('fr-FR', options);
            }
        });
    }
    
    // Confirmation de suppression
    const deleteButtons = document.querySelectorAll('.delete-event');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});

// Fonction pour copier le lien d'invitation
function copyInvitationLink(link) {
    navigator.clipboard.writeText(link).then(function() {
        alert('Lien copié dans le presse-papier !');
    }, function() {
        alert('Erreur lors de la copie');
    });
}

// Fonction pour envoyer des rappels
function sendReminders(eventId) {
    if (confirm('Envoyer des rappels à tous les invités n\'ayant pas répondu ?')) {
        fetch(`/api/send-reminders.php?event_id=${eventId}`, {
            method: 'POST'
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert('Rappels envoyés avec succès !');
              } else {
                  alert('Erreur lors de l\'envoi des rappels');
              }
          });
    }
}