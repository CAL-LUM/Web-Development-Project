// Function to handle sign out
function signOut() {
    // Send AJAX request to signout.php
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/php/signout.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Redirect to signin.html after successful sign out
            window.location.href = "../index.html";
        }
    };
    xhr.send();
}

// Add event listener to "Sign out" button
document.querySelector('.mini-menu-btn').addEventListener('click', signOut);
