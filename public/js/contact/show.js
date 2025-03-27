document.addEventListener("DOMContentLoaded", function() {
    // Elements
    const deleteBtn = document.getElementById('delete-btn');
    const deleteModal = document.getElementById('delete-modal');
    const cancelDelete = document.getElementById('cancel-delete');
    const deleteForm = document.getElementById('delete-form');
    const sendMessageBtn = document.getElementById('send-message-btn');
    const messageModal = document.getElementById('message-modal');
    const cancelMessage = document.getElementById('cancel-message');
    const messageContent = document.getElementById('message-content');
    const sendWhatsapp = document.getElementById('send-whatsapp');
    const successMessage = document.getElementById('success-message');

    // Show delete confirmation modal
    deleteBtn.addEventListener('click', function() {
        deleteModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Hide delete modal
    cancelDelete.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
        document.body.style.overflow = '';
    });

    // Show message modal
    sendMessageBtn.addEventListener('click', function() {
        messageModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Hide message modal
    cancelMessage.addEventListener('click', function() {
        messageModal.classList.add('hidden');
        document.body.style.overflow = '';
    });

    // Update WhatsApp link with message
    messageContent.addEventListener('input', function() {
        const message = encodeURIComponent(this.value);
        const originalUrl = sendWhatsapp.getAttribute('href').split('?')[0];
        sendWhatsapp.setAttribute('href', `${originalUrl}?text=${message}`);
    });

    // Close modals when clicking outside
    [deleteModal, messageModal].forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    // Close success message after 5 seconds
    if (successMessage && !successMessage.classList.contains('hidden')) {
        setTimeout(() => {
            successMessage.classList.add('hidden');
        }, 5000);
    }

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [deleteModal, messageModal].forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
    });
});