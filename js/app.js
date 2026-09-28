/**
 * Sistema de Inventario - JavaScript
 * Ubicación: js/app.js
 * Maneja animaciones, validaciones y interacciones
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // ANIMACIONES DE ENTRADA CON INTERSECTION OBSERVER
    // ============================================
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observar elementos animables
    const animatedElements = document.querySelectorAll('.stat-card, .table tbody tr, .form-container, .alert');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = `all 0.6s ease-out ${index * 0.1}s`;
        observer.observe(el);
    });

    // ============================================
    // VALIDACIÓN DE FORMULARIOS
    // ============================================
    
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--peligro)';
                    
                    // Agregar mensaje de error
                    let errorMsg = field.parentElement.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('small');
                        errorMsg.className = 'error-message';
                        errorMsg.style.color = 'var(--peligro)';
                        errorMsg.style.fontSize = '0.85rem';
                        errorMsg.style.marginTop = '0.3rem';
                        errorMsg.style.display = 'block';
                        errorMsg.textContent = 'Este campo es obligatorio';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.style.borderColor = 'var(--gris)';
                    const errorMsg = field.parentElement.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                }
            });

            if (!isValid) {
                e.preventDefault();
                showNotification('Por favor, completa todos los campos requeridos', 'danger');
            }
        });

        // Limpiar error al escribir
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.style.borderColor = 'var(--gris)';
                const errorMsg = this.parentElement.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            });
        });
    });

    // ============================================
    // CONFIRMACIÓN DE ELIMINACIÓN
    // ============================================
    
    const deleteLinks = document.querySelectorAll('a[href*="eliminar_producto"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar este producto?\n\nEsta acción no se puede deshacer.')) {
                window.location.href = this.href;
            }
        });
    });

    // ============================================
    // EFECTOS HOVER EN BOTONES
    // ============================================
    
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // ============================================
    // NOTIFICACIONES AUTOMÁTICAS DESDE URL
    // ============================================
    
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('deleted') === '1') {
        showNotification('Producto eliminado exitosamente', 'success');
    }
    if (urlParams.get('error') === '1') {
        showNotification('Error al procesar la solicitud', 'danger');
    }
    if (urlParams.get('updated') === '1') {
        showNotification('Producto actualizado exitosamente', 'success');
    }
    if (urlParams.get('created') === '1') {
        showNotification('Producto creado exitosamente', 'success');
    }

    // ============================================
    // BÚSQUEDA EN TABLA (si existe campo de búsqueda)
    // ============================================
    
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // ============================================
    // TOOLTIP PARA BADGES DE STOCK
    // ============================================
    
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

/**
 * Muestra una notificación temporal
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo: success, danger, warning, info
 */
function showNotification(message, type = 'info') {
    // Remover notificaciones existentes
    const existing = document.querySelector('.notification-toast');
    if (existing) existing.remove();

    const notification = document.createElement('div');
    notification.className = `notification-toast alert alert-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        animation: slideInRight 0.4s ease-out;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'all 0.5s ease';
        setTimeout(() => notification.remove(), 500);
    }, 3500);
}

/**
 * Formatea números como moneda
 * @param {number} amount
 * @returns {string}
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

/**
 * Confirma una acción con el usuario
 * @param {string} message
 * @returns {boolean}
 */
function confirmAction(message) {
    return confirm(message);
}