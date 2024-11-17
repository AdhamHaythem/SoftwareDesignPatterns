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

function donationHistory(itemId) {
    $.ajax({
        url: "../Controller/AdminController.php" ,
        type: 'POST',
        data: {
            donationHistory: ''
            },
        });
}

function generateReports(itemId) {
$.ajax({
    url: "../Controller/AdminController.php",
    type: 'POST',
    data: {
        generateReports: ''
        },
    });
}


function viewDonationStatistics(itemId) {
    $.ajax({
        url: "../Controller/AdminController.php",
        type: 'POST',
        data: {
            viewDonationStatistics: '',
            },
        });
}


function getCampaignData(itemId) {
    $.ajax({
        url: "../Controller/EventController.php",
        type: 'POST',
        data: {
            retrieveCampaign: '',
            eventId : itemId,
            },
        });
}


function donationDetails($donationId) {
    $.ajax({
        url: '../Controller/DonationController.php',
        type: 'POST',
        data: {
            donationId: $donationId,
            donationDetails: '',
        },
    });
}

function donorProfile($donorId) {
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            donorId: $donorId,
            retrieveDonor: '',
            },
        });
}

function donationHistory(itemId) {
$.ajax({
url: "../Controller/DonorController.php" ,
type: 'POST',
data: {
    getDonationHistory: '',
    donorId:itemId,
    },
});
}

function totalDonations(itemId) {
$.ajax({
    url: "../Controller/DonorController.php",
    type: 'POST',
    data: {
        getTotalDonations: '',
        donorId:itemId,
        },
    });
};

function login() {
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            email: getElementById("exampleInputEmail1").text,
            password: getElementById("exampleInputPassword1").text,
            login: '',
            },
        });
    };

function signup(itemId) {
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            username: getElementById("username").text,
            firstname: getElementById("validationServer01").text,
            lastname: getElementById("validationServer02").text,
            userId: getElementById("validationServer01").text,
            email: getElementById("validationServerEmail").text,
            password: getElementById("password").text,
            location: getElementById("validationServer01").text,
            phoneNumber: getElementById("phoneNumber").text,
            signup: '',
            },
            success: function(response) {
                $('.container').html(response);
            },
        });
    };

function displayLogin(){
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            displayLogin: '',
            },
            success: function(response) {
                $('.container').html(response);
            },
        });
    };

function displaysignUp(){
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            displaysignUp: '',
            },
        success: function(response) {
            $('.container').html(response);
        },
        });
    };
