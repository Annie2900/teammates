// A mennyiségek alapján számítsuk ki és frissítsük a végösszeget
    document.addEventListener('DOMContentLoaded', function () {
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const grandTotalElement = document.getElementById('grand-total');

        function updateGrandTotal() {
            let grandTotal = 0;
            quantityInputs.forEach(input => {
                const pricePerUnit = parseFloat(input.getAttribute('data-price'));
                const quantity = parseInt(input.value);
                const subtotal = pricePerUnit * quantity;
                grandTotal += subtotal;
            });
            grandTotalElement.textContent = grandTotal.toFixed(2);
        }

        quantityInputs.forEach(input => {
            input.addEventListener('input', updateGrandTotal);
        });

        // Oldal betöltésekor inicializáljuk a végösszeget
        updateGrandTotal();
    });