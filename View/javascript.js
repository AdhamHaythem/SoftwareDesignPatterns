function users(userId) {
    $.ajax({
        url: "../Controller/UserController.php",
        type: 'POST',
        data: {
            users: '' 
        },
        success: function(response) {
            console.log("Server response:", response);
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


function viewDonationStatistics(itemId) {
    $.ajax({
        url: "../Controller/AdminController.php",
        type: 'POST',
        data: {
            viewDonationStatistics: '',
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
};

function donationReport(itemId) {
    $.ajax({
        url: '../Controller/DonationManagerController.php',
        type: 'POST',
        data: {
            generateDonationReport: '',
        },
    });
};

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
            retrieveDonor: '',
            },
        });
    };

function signup() {
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
            createUser: '',
            Donor: '',
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

function campaignDetails(campId){
    $.ajax({
        url: "../Controller/EventController.php",
        type: 'POST',
        data: {
            retrieveCampaign: '',
            eventId: campId,
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function fundProgress(campId){
    $.ajax({
        url: "../Controller/EventController.php",
        type: 'POST',
        data: {
            fundProgress: '',
            campaignId: campId,
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function addfundProgress(campId){
    $.ajax({
        url: "../Controller/EventController.php",
        type: 'POST',
        data: {
            addFunds: '',
            amount: getElementById("amountRaised").text,
            eventId: campId,
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function volunteerDetails(campId){
    $.ajax({
        url: "../Controller/EventController.php",
        type: 'POST',
        data: {
            retrieveCampaign: '',
            eventId: campId,
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};



function eventStatus(statusId) {
    $.ajax({
        url: "../Controller/DonorController.php",
        type: 'POST',
        data: {
            eventId: statusId,
            status: '',
            },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function eventReport(reportId) {
    $.ajax({
        url: "../Controller/DonorController.php",
        type: 'POST',
        data: {
            eventId: reportId,
            eventReport: '',
            },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function eventList(itemId) {
    $.ajax({
        url: "../Controller/DonorController.php",
        type: 'POST',
        data: {
            eventList: '',
            donorId: itemId,
            },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function enrollLesson(itemId) {
    $.ajax({
        url: "../Controller/StudentController.php",
        type: 'POST',
        data: {
            enrollLesson: '',
            lessonId: itemId,
            studendId: itemId,
            instructorId:itemId,
            },
        success: function(response) {
            $('.container').html(response);
            alert("Enrolled in Lesson Name: ' 1 '");
        },
    });
};

function LessonDetails(itemId) {
    $.ajax({
        url: '../Controller/LessonController.php',
        type: 'POST',
        data: {
            lessonId: itemId,
            retrieve: '',
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function EnrolledLessons(itemId) {
    $.ajax({
        url: '../Controller/StudentController.php',
        type: 'POST',
        data: {
            lessonId: itemId,
            getlessonList: '',
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function LessonsEnstructor(itemId) {
    $.ajax({
        url: '../Controller/InstructorController.php',
        type: 'POST',
        data: {
            lessonId: itemId,
            instructorId: itemId,
            retrieveLesson: '',
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function AllLessonsEnstructor(itemId) {
    $.ajax({
        url: '../Controller/InstructorController.php',
        type: 'POST',
        data: {
            instructorId: itemId,
            getLessons: '',
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function donationButton(itemId) {
    
    const donationTypes = ["food", "clothes", "medical", "cash"];
    const donationData = {};

    // Loop through each donation type
    donationTypes.forEach((type) => {
        const element = document.getElementById(type); // Get the checkbox element
        donationData[type] = element && element.checked ? element.value : ""; // Send value if checked, empty string otherwise
    });

    $.ajax({
        url: '../Controller/DonorController.php',
        type: 'POST',
        data: {
            donorId: itemId,
            addDonation: '',
            ...donationData, // Spread the donation data dynamically
            amount: getElementById('amount').text,
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function undo(itemId) {
    $.ajax({
        url: '../Controller/DonorController.php',
        type: 'POST',
        data: {
            changeDonation: '',
            undo: '',
            donorId: itemId, 
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function redo(itemId) {
    $.ajax({
        url: '../Controller/DonorController.php',
        type: 'POST',
        data: {
            changeDonation: '',
            redo: '',
            donorId: itemId, 
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function joinCampaign(itemId,eventId) {
    $.ajax({
        url: '../Controller/EventController.php',
        type: 'POST',
        data: {
            addVolunteerForCampaign: '',
            donorId: itemId,
            eventId: eventId, 
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};

function joinVolunteer(itemId,eventId) {
    $.ajax({
        url: '../Controller/EventController.php',
        type: 'POST',
        data: {
            addVolunteerForVolunteeringEvent: '',
            donorId: itemId,
            eventId: eventId, 
        },
        success: function(response) {
            $('.container').html(response);
        },
    });
};