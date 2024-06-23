$(document).ready(function() {
        $('#editProfileForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            // Get form data
            var formData = {
                'firstname': $('#firstName').val(),
                'lastname': $('#lastName').val(),
                'phone': $('#phone').val(),
                'city': $('#city').val(),
                'street': $('#street').val()
            };

            // AJAX request to update profile
            $.ajax({
                type: 'POST',
                url: 'save_profile.php',
                data: formData,
                dataType: 'json',
                encode: true
            })
                .done(function(response) {
                    // Handle success response
                    if (response.success) {
                        // Update UI with new data if necessary
                        // For example, update profile fields
                        $('#editProfileModal').modal('hide'); // Hide the modal
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        // Handle error scenario
                        console.error('Update failed:', response.message);
                        // Optionally display error message to the user
                        alert('Profil frissítése sikertelen. Kérjük, próbálja újra később.');
                    }
                })
                .fail(function(xhr, status, error) {
                    // Handle AJAX request failure
                    console.error('AJAX request failed:', error);
                    // Optionally display error message to the user
                    alert('Hiba történt a szerver kommunikáció során. Kérjük, próbálja újra később.');
                });
        });
    });