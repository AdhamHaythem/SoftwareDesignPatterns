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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
        </head>
        <body>
            <div id="eventModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Event Options</h2>
                    <div class="modal-buttons">
                        <button onclick="loadEventStatus()">View Event Status</button>
                        <button onclick="loadEventReport()">View Event Report</button>
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
                    // Store event name for use in loading status or report
                    sessionStorage.setItem("selectedEvent", eventName);
                }

                function closeModal() {
                    document.getElementById("eventModal").style.display = "none";
                }

                // Load event status
                // function loadEventStatus() {
                //     let eventName = sessionStorage.getItem("selectedEvent");
                //     fetch("EventController.php?action=getEventStatus&event=" + encodeURIComponent(eventName))
                //         .then(response => response.text())
                //         .then(data => {
                //             document.getElementById("eventModal").style.display = "none";
                //             document.getElementById("content").innerHTML = data;
                //         })
                //         .catch(error => console.error("Error:", error));
                // }

                // Load event report
                // function loadEventReport() {
                //     let eventName = sessionStorage.getItem("selectedEvent");
                //     fetch("EventController.php?action=getEventReport&event=" + encodeURIComponent(eventName))
                //         .then(response => response.text())
                //         .then(data => {
                //             document.getElementById("eventModal").style.display = "none";
                //             document.getElementById("content").innerHTML = data;
                //         })
                //         .catch(error => console.error("Error:", error));
                }
            </script>

        </body>
        </html>';
    }


    public function displayEventStatus($event)
    {
        echo '<div class="event-status">';
        echo '<h2>Event Status: ' . htmlspecialchars($event->name) . '</h2>';
        echo '<p><strong>Status:</strong> ' . htmlspecialchars($event->status) . '</p>';
        echo '</div>';

        
        echo "<script>function eventStatus(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/View/EventController.php\" ?>,
                type: \'POST\',
                data: {
                    eventId: \'<?php echo $event -> eventId ?>\',
                    eventStatus: \'\',
                    },
                });
            };<\script>";
    }

    public function displayEventReport($event)
    {
        echo '<div class="event-report">';
        echo '<h2>Event Report: ' . htmlspecialchars($event->name) . '</h2>';
        echo '<p><strong>Date:</strong> ' . htmlspecialchars($event->date) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($event->location) . '</p>';
        echo '<p><strong>Description:</strong> ' . htmlspecialchars($event->description) . '</p>';
        echo '<p><strong>Total Donations:</strong> ' . htmlspecialchars($event->totalDonations) . '</p>';
        echo '</div>';

        echo "<script>function eventReport(itemId) {
            $.ajax({
                url: <?php echo \"SoftwareDesignPatterns/View/EventController.php\" ?>,
                type: \'POST\',
                data: {
                    eventId: \'<?php echo $event -> eventId ?>\',
                    eventReport: \'\',
                    },
                });
            };<\script>";
    }
}

// Usage example
$eventView = new EventView();
$eventView->EventViewDetails("Sample Data");

?>
