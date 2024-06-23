// user.js

// Function to show the popup form
function showPopupForm() {
    document.getElementById('popupForm').style.display = 'block';
}

// Function to close the popup form
function closePopupForm() {
    document.getElementById('popupForm').style.display = 'none';
}

// Function to create a new user
function createNewUser() {
    const firstname = document.getElementById('newFirstname').value;
    const lastname = document.getElementById('newLastname').value;
    const phone = document.getElementById('newPhone').value;
    const email = document.getElementById('newEmail').value;
    const password = document.getElementById('newPassword').value;

    // Construct JSON data to send
    const data = {
        firstname: firstname,
        lastname: lastname,
        phone: phone,
        email: email,
        password: password,
        is_banned: 0, // Default to 0 for new users
        active: 1 // Default to 1 for new users
    };

    // Check if email is already in use
    fetch(`check_email.php?email=${encodeURIComponent(email)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            if (result.exists) {
                alert('Email is already registered. Please choose a different email.');
            } else {
                // Proceed with creating the new user
                fetch('save_new_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.message) {
                        alert(result.message); // Display success message
                        closePopupForm(); // Close popup after success
                        clearFormFields(); // Clear form fields after success
                    } else if (result.error) {
                        alert(result.error); // Display error message
                    }
                })
                .catch(error => {
                    console.error('Error saving new user:', error);
                    alert('Failed to create user. Please try again.');
                });
            }
        })
        .catch(error => {
            console.error('Error checking email availability:', error);
            alert('Failed to check email availability. Please try again.');
        });
}

// Function to clear form fields in the popup form
function clearFormFields() {
    document.getElementById('newFirstname').value = '';
    document.getElementById('newLastname').value = '';
    document.getElementById('newPhone').value = '';
    document.getElementById('newEmail').value = '';
    document.getElementById('newPassword').value = '';
}

// Function to clear the main form
function clearForm() {
    document.getElementById('firstname').value = '';
    document.getElementById('lastname').value = '';
    document.getElementById('phone').value = '';
    document.getElementById('email').value = '';
    document.getElementById('is_banned').value = '';

    // Hide the form
    document.getElementById('userForm').style.display = 'none';

    // Clear search bar
    document.getElementById('userEmail').value = '';
}

// Function to search for email
function searchEmail() {
    const input = document.getElementById('userEmail').value;
    const emailList = document.getElementById('emailList');
    
    if (input.length > 0) {
        fetch(`search_email.php?query=${input}`)
            .then(response => response.json())
            .then(data => {
                emailList.innerHTML = '';
                data.forEach(email => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = email;
                    li.onclick = () => fetchUserData(email);
                    emailList.appendChild(li);
                });
            })
            .catch(error => console.error('Error:', error));
    } else {
        emailList.innerHTML = '';
        // Hide the user form if the search bar is cleared
        document.getElementById('userForm').style.display = 'none';
    }
}

// Function to fetch user data
function fetchUserData(email) {
    fetch(`get_user_data.php?email=${email}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('firstname').value = data.firstname;
            document.getElementById('lastname').value = data.lastname;
            document.getElementById('phone').value = data.phone;
            document.getElementById('email').value = data.email;
            document.getElementById('is_banned').value = data.is_banned;

            // Fill the search bar with the selected email and hide the dropdown
            document.getElementById('userEmail').value = email;
            document.getElementById('emailList').innerHTML = '';

            // Show the user form
            document.getElementById('userForm').style.display = 'block';
        })
        .catch(error => console.error('Error:', error));
}

// Function to save changes
function saveChanges() {
    // Retrieve form data
    const firstname = document.getElementById('firstname').value;
    const lastname = document.getElementById('lastname').value;
    const phone = document.getElementById('phone').value;
    const email = document.getElementById('email').value;
    const is_banned = document.getElementById('is_banned').value;

    // Construct JSON data to send
    const data = {
        email: email,
        firstname: firstname,
        lastname: lastname,
        phone: phone,
        is_banned: is_banned
    };

    fetch('save_changes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        // Optionally handle any specific logic after saving
        console.log('Changes saved successfully:', result);

        // Hide the form after saving
        document.getElementById('userForm').style.display = 'none';

        // Clear search bar
        document.getElementById('userEmail').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
        // Optionally, handle errors or display an error message
    });
}

// Function to delete user
function deleteUser() {
    const email = document.getElementById('email').value;

    if (confirm(`Are you sure you want to delete user with email ${email}?`)) {
        fetch('delete_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => {
            // Check if response is valid JSON
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response
        })
        .then(result => {
            // Handle success or error message
            if (result.message) {
                console.log(result.message); // Log success message
                // Optionally, display success message to user
            } else if (result.error) {
                console.error(result.error); // Log error message
                // Optionally, display error message to user
            }
            clearForm(); // Clear form after deletion
        })
        .catch(error => {
            console.error('Error:', error);
            // Handle network errors or server-side issues
        });
    }
}
