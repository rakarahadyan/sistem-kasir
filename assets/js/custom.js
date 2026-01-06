// Custom JavaScript for the system
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-focus on barcode scanner
    $('#scanBarcode').focus();
    
    // Format numbers
    $('.format-number').on('blur', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            $(this).val(parseInt(value).toLocaleString('id-ID'));
        }
    });
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    $('.content-header').after(notification);
    
    setTimeout(() => {
        $('.alert').alert('close');
    }, 3000);
}

// Confirm before delete
function confirmDelete(message = 'Yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Format currency
function formatCurrency(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

// API integration example
async function fetchProducts() {
    try {
        const response = await fetch('api/products.php');
        const products = await response.json();
        return products;
    } catch (error) {
        console.error('Error fetching products:', error);
        return [];
    }
}