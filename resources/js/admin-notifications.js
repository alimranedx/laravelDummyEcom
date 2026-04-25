
document.addEventListener('DOMContentLoaded', function () {
    const notificationContainer = document.getElementById('notification-data');
    if (!notificationContainer) return;

    let unreadCount = parseInt(notificationContainer.dataset.unreadCount || 0);
    const markAsReadUrl = notificationContainer.dataset.markAsReadUrl;
    const csrfToken = notificationContainer.dataset.csrfToken;

    if (window.Echo) {
        window.Echo.channel('admin-notifications')
            .listen('.SaleCreated', (e) => {
                showToast(e.notification.message, 'success');
                addNotificationToDropdown(e.notification.message, 'success', e.notification.url);
            })
            .listen('.UserRegistered', (e) => {
                showToast(e.notification.message, 'info');
                addNotificationToDropdown(e.notification.message, 'info', e.notification.url);
            });
    }

    function showToast(message, type) {
        const toastEl = document.getElementById('liveToast');
        const toastMessage = document.getElementById('toastMessage');
        
        if (!toastEl || !toastMessage) return;

        toastEl.className = `toast align-items-center border-0 text-bg-${type}`;
        toastMessage.innerText = message;
        
        // Use the global bootstrap if available, otherwise try window.bootstrap
        const bootstrapInstance = window.bootstrap || (typeof bootstrap !== 'undefined' ? bootstrap : null);
        if (bootstrapInstance && bootstrapInstance.Toast) {
            const toast = new bootstrapInstance.Toast(toastEl);
            toast.show();
        } else {
            // Fallback: just show the element
            toastEl.classList.add('show');
            setTimeout(() => toastEl.classList.remove('show'), 5000);
        }
    }

    function addNotificationToDropdown(message, type, url = '#') {
        const noNotificationsMsg = document.getElementById('noNotificationsMsg');
        if (noNotificationsMsg) {
            noNotificationsMsg.remove();
        }

        const list = document.getElementById('notificationList');
        if (!list) return;

        const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const item = document.createElement(url !== '#' ? 'a' : 'li');
        if (url !== '#') {
            item.href = url;
        }
        item.className = 'px-3 py-2 border-bottom d-flex align-items-start dropdown-item text-wrap text-decoration-none';
        item.innerHTML = `
            <div class="me-2 text-${type}"><i class="bi bi-bell-fill"></i></div>
            <div>
                <div class="small text-dark fw-medium">${message}</div>
                <div class="text-muted" style="font-size: 0.75rem;">${time}</div>
            </div>
        `;
        
        // Add to list right after the header (index 0 is "Notifications" title)
        if (list.children.length > 1) {
            list.insertBefore(item, list.children[1]);
        } else {
            list.appendChild(item);
        }

        // Maintain max 10 items
        if (list.children.length > 11) {
            list.removeChild(list.lastElementChild);
        }

        // Update badge
        unreadCount++;
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.style.display = 'inline-block';
        }
    }

    // Initialize manual dropdown toggle
    const dropdownBtn = document.getElementById('notificationDropdown');
    const dropdownMenu = document.getElementById('notificationList');

    if (dropdownBtn && dropdownMenu) {
        dropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isShown = dropdownMenu.classList.contains('show');
            
            if (isShown) {
                dropdownMenu.classList.remove('show');
                dropdownBtn.setAttribute('aria-expanded', 'false');
            } else {
                dropdownMenu.classList.add('show');
                dropdownBtn.setAttribute('aria-expanded', 'true');
                
                if (unreadCount > 0) {
                    // Reset count when opening
                    unreadCount = 0;
                    const badge = document.getElementById('notificationBadge');
                    if (badge) badge.style.display = 'none';
                    
                    // Remove unread styling
                    document.querySelectorAll('.unread-indicator').forEach(el => el.remove());
                    document.querySelectorAll('.notification-item .fw-bold').forEach(el => el.classList.remove('fw-bold'));

                    // Mark as read in backend
                    if (markAsReadUrl) {
                        fetch(markAsReadUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });
                    }
                }
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (dropdownBtn && !dropdownBtn.contains(e.target) && dropdownMenu && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
                dropdownBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
