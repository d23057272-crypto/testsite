document.addEventListener('DOMContentLoaded', function() {
    function updateNotificationBadges() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                // Update mobile notification badge
                const mobileBadge = document.getElementById('mobile-profile-notification-badge');
                if (mobileBadge) {
                    if (data.count > 0) {
                        mobileBadge.textContent = data.count > 9 ? '9+' : data.count;
                        mobileBadge.classList.remove('hidden');
                    } else {
                        mobileBadge.classList.add('hidden');
                    }
                }
                
                // Update sidebar notification badge
                const sidebarBadge = document.getElementById('sidebar-notification-badge');
                if (sidebarBadge) {
                    if (data.count > 0) {
                        sidebarBadge.textContent = data.count > 9 ? '9+' : data.count;
                        sidebarBadge.classList.remove('hidden');
                    } else {
                        sidebarBadge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));
    }

    // Initial load
    updateNotificationBadges();

    // Periodic updates every minute
    setInterval(updateNotificationBadges, 60 * 1000);
    
    // Handle modals
    window.toggleModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    };
    
    // Handle wallet dropdown
    window.toggleWalletDropdown = function() {
        const dropdown = document.getElementById('walletDropdown');
        const icon = document.getElementById('dropdownIcon');
        if (dropdown && icon) {
            dropdown.classList.toggle('hidden');
            icon.classList.toggle('transform');
            icon.classList.toggle('rotate-180');
            
            // Close dropdown when clicking outside
            if (!dropdown.classList.contains('hidden')) {
                document.addEventListener('click', function closeDropdown(event) {
                    if (!event.target.closest('#walletDropdown') && !event.target.closest('button[onclick="toggleWalletDropdown()"]')) {
                        dropdown.classList.add('hidden');
                        icon.classList.remove('transform', 'rotate-180');
                        document.removeEventListener('click', closeDropdown);
                    }
                });
            }
        }
    };
    
    // Copy account ID functionality
    window.copyAccountId = function() {
        const accountId = document.getElementById('accountId');
        if (accountId) {
            navigator.clipboard.writeText(accountId.textContent).then(() => {
                // Show toast or notification that ID was copied
                alert('Address copied to clipboard!');
            });
        }
    };
    
    // Modal close buttons
    const closeSendModal = document.getElementById('closeSendModal');
    if (closeSendModal) {
        closeSendModal.onclick = function() {
            toggleModal('sendModal');
        };
    }

    const closeReceiveModal = document.getElementById('closeReceiveModal');
    if (closeReceiveModal) {
        closeReceiveModal.onclick = function() {
            toggleModal('receiveModal');
        };
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const sendModal = document.getElementById('sendModal');
        const receiveModal = document.getElementById('receiveModal');

        if (sendModal && event.target === sendModal) {
            sendModal.classList.add('hidden');
        }

        if (receiveModal && event.target === receiveModal) {
            receiveModal.classList.add('hidden');
        }
    });
});
        