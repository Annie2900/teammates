function openModal(orderId) {
            console.log('Fetching data for order ID:', orderId);
            fetch('fetch_ordered_food.php?order_id=' + orderId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);
                    if (data.error) {
                        console.error('Error from server:', data.error);
                        alert('Error: ' + data.error); // Display error message to the user
                        return;
                    }
                    const modalBody = document.getElementById('modal-body');
                    modalBody.innerHTML = ''; // Clear previous content
                    data.forEach(item => {
                        const foodItem = document.createElement('div');
                        foodItem.classList.add('food-item');
                        foodItem.textContent = item.food_name;
                        modalBody.appendChild(foodItem);
                    });
                    document.getElementById('myModal').style.display = 'block';
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }