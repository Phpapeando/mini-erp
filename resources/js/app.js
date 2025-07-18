document.addEventListener('DOMContentLoaded', function() {
    const buyButton = document.getElementById('buy-button');
    
    if (buyButton) {
        buyButton.addEventListener('click', function() {
            alert('Product added to cart!');
        });
    }
});