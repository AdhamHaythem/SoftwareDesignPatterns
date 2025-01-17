<?php

class EventView
{
    public function EventViewDetails($StdObj)
    { 
        echo '<!DOCTYPE html>
        <html lang="en" dir="ltr">
        <head>
            <meta charset="utf-8">
            <title>Event Information</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="style3.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <div id="eventModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Event Options</h2>
                    <div class="modal-buttons">
                        <button onclick="eventStatus(1)">View Event Status</button>
                        <button onclick="eventReport(1)">View Event Report</button>
                    </div>
                </div>
            </div>

            <div id="content">
                <h2>Welcome to the Events Information System</h2>
                <p>Select an event from the list below to view more details.</p>
            </div>

            <script>
                function openModal(eventName) {
                    document.getElementById("eventModal").style.display = "block";
                    sessionStorage.setItem("selectedEvent", eventName);
                }

                function closeModal() {
                    document.getElementById("eventModal").style.display = "none";
                }
            </script>

        </body>
        </html>';
    }

    public function displayEventReport($event)
{
    echo '<div class="container">
            <section>
                <div class="lesson-details">
                    <h2>Event Report: ' . $event->name . '</h2>
                    <p><strong>Date:</strong> ' . $event->date . '</p>
                    <p><strong>Location:</strong> ' . $event->location . '</p>
                    <p><strong>Description:</strong> ' . $event->description . '</p>
                    <p><strong>Total Donations:</strong> $' . $event->totalDonations . '</p>
                </div>
            </section>
        </div>';
}

}

// Usage example
$eventView = new EventView();
$eventView->EventViewDetails("Sample Data");
$eventView->displayEventReport("1");


?>
