 document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('secret-container').style.display = 'block';
                    document.getElementById('message').innerHTML = ''; // Clear any previous error messages
                    console.log('Email:', data.email); // Log the email to the console for debugging
                } else {
                    document.getElementById('message').innerHTML = '<p class="error">' + data.message + '</p>';
                    console.log('Email:', data.email); // Log the email to the console for debugging
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('secret-form').addEventListener('submit', function(event) {
        });