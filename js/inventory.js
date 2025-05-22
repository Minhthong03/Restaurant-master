document.addEventListener('DOMContentLoaded', function() {
    const ingredientSelect = document.getElementById('ingredientSelect');
    const quantityInput = document.getElementById('quantityInput');
    const totalInput = document.getElementById('totalInput');

    function updateTotal() {
        const selectedId = ingredientSelect.value;
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = window.unitPrices[selectedId] || 0;
        const total = price * quantity;
        totalInput.value = total.toFixed(2);
    }

    if (ingredientSelect && quantityInput && totalInput) {
        ingredientSelect.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);
    }
});
