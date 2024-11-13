function users(userId) {
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            users: ''  // Add any necessary data here
        },
        success: function(response) {
            console.log("Server response:", response);
            // Display the response in a specific div or handle it as needed
            document.getElementById("content").innerHTML = response;
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", error);
        }
    });
}
