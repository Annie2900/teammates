// Kedvenc jelölő kezelése
    function toggleFavorite(foodId) {
        var starElement = document.querySelector('.favorite-star[data-food-id="' + foodId + '"]');
        var isSelected = starElement.classList.toggle('selected');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'food.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = xhr.responseText.trim();
                if (response === 'success') {
                    showPopupMessage('Kedvenc étel hozzáadva!');
                } else if (response === 'removed') {
                    showPopupMessage('Kedvenc étel eltávolítva!');
                } else if (response === 'exists') {
                    showPopupMessage('Az étel már a kedvenceid között van.');
                } else {
                    console.error('Hiba történt a kedvenc állapot frissítése közben.');
                }
            }
        };
        xhr.send('favorite_food_id=' + foodId);
    }

    // Funkció a popup üzenet megjelenítésére és eltűntetésére
    function showPopupMessage(message) {
        var popup = document.getElementById('popup');
        popup.innerHTML = message;
        popup.style.display = 'block';
        setTimeout(function() {
            popup.style.display = 'none';
        }, 2000); // Popup eltűntetése 2 másodperc után
    }

    // Form submission handling for orderForm
    var form = document.getElementById('orderForm');
    form.addEventListener('submit', function(event) {
        var checkboxes = document.querySelectorAll('input[name="food_ids[]"]:checked');
        if (checkboxes.length === 0) {
            alert('Kérjük, válasszon ételeket a rendeléshez!');
            event.preventDefault(); // Prevent form submission
        } else {
            // Continue with form submission
            // You may add additional logic here if needed
        }
    });