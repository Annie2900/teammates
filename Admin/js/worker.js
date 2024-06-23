// worker.js

// Function to show the popup form
function showPopupForm() {
    document.getElementById('popupForm').style.display = 'block';
}

// Function to close the popup form
function closePopupForm() {
    document.getElementById('popupForm').style.display = 'none';
}

// Function to create a new delivery person
function createNewWorker() {
    const firstname = document.getElementById('newDelFirstname').value;
    const lastname = document.getElementById('newDelLastname').value;
    const email = document.getElementById('newDelEmail').value;
    const password = document.getElementById('newDelPassword').value;

    // Construct JSON data to send
    const data = {
        firstname: firstname,
        lastname: lastname,
        email: email,
        password: password
    };

    // Check if email is already in use
    fetch(`check_email_worker.php?email=${encodeURIComponent(email)}`)
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
                // Proceed with creating the new delivery person
                fetch('save_new_worker.php', {
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
                        clearWorkerFormFields(); // Clear form fields after success
                    } else if (result.error) {
                        alert(result.error); // Display error message
                    }
                })
                .catch(error => {
                    console.error('Error saving new delivery person:', error);
                    alert('Failed to create delivery person. Please try again.');
                });
            }
        })
        .catch(error => {
            console.error('Error checking email availability:', error);
            alert('Failed to check email availability. Please try again.');
        });
}


// Function to clear form fields in the popup form
function clearWorkerFormFields() {
    document.getElementById('newDelFirstname').value = '';
    document.getElementById('newDelLastname').value = '';
    document.getElementById('newDelEmail').value = '';
    document.getElementById('newDelPassword').value = '';
}

// Function to clear the main form
function clearWorkerForm() {
    document.getElementById('delFirstname').value = '';
    document.getElementById('delLastname').value = '';
    document.getElementById('delEmail').value = '';

    // Hide the form
    document.getElementById('workerForm').style.display = 'none';

    // Clear search bar
    document.getElementById('workerEmail').value = '';
}

// Function to search for email
function searchEmail() {
    const input = document.getElementById('workerEmail').value;
    const emailList = document.getElementById('emailList');
    
    if (input.length > 0) {
        fetch(`search_email_worker.php?query=${input}`)
            .then(response => response.json())
            .then(data => {
                emailList.innerHTML = '';
                data.forEach(email => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = email;
                    li.onclick = () => {
                        document.getElementById('workerEmail').value = email; // Fill the search bar with the clicked email
                        emailList.innerHTML = ''; // Hide the dropdown list
                        fetchWorkerData(email); // Fetch and display worker data
                    };
                    emailList.appendChild(li);
                });
            })
            .catch(error => console.error('Error:', error));
    } else {
        emailList.innerHTML = '';
        // Hide the worker form if the search bar is cleared
        document.getElementById('workerForm').style.display = 'none';
    }
}

// Function to fetch delivery person data
function fetchWorkerData(email) {
    fetch(`get_worker_data.php?email=${email}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('delFirstname').value = data.del_fname || '';
                document.getElementById('delLastname').value = data.del_lname || '';
                document.getElementById('delEmail').value = data.del_email || '';

                // Fill the search bar with the selected email
                document.getElementById('workerEmail').value = email;

                // Hide the dropdown list of search results
                document.getElementById('emailList').innerHTML = '';

                // Show the worker form
                document.getElementById('workerForm').style.display = 'block';
            } else {
                console.error('No data found for the provided email');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to save changes for worker
function saveWorkerChanges() {
    // Retrieve form data
    const firstname = document.getElementById('delFirstname').value;
    const lastname = document.getElementById('delLastname').value;
    const email = document.getElementById('delEmail').value;

    // Construct JSON data to send
    const data = {
        email: email,
        firstname: firstname,
        lastname: lastname
    };

    fetch('save_changes_worker.php', {
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
            alert(result.message);
        } else if (result.error) {
            alert(result.error);
        }

        document.getElementById('workerForm').style.display = 'none';
        document.getElementById('workerEmail').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}

// Function to delete worker
function deleteWorker() {
    const email = document.getElementById('delEmail').value;

    if (!email) {
        alert('No worker selected.');
        return;
    }

    if (!confirm('Are you sure you want to delete this worker?')) {
        return;
    }

    fetch('delete_worker.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => {
        console.log('Response Status:', response.status); // Debugging log
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse JSON response
    })
    .then(result => {
        console.log('Server Response:', result); // Debugging log
        if (result.message) {
            alert(result.message);
        } else if (result.error) {
            alert(result.error);
        }

        // Hide the form after deletion
        document.getElementById('workerForm').style.display = 'none';

        // Clear search bar
        document.getElementById('workerEmail').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}
