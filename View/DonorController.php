<?php
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getDonationHistory':
            echo json_encode(["Donation 1", "Donation 2", "Donation 3"]);
            break;
        case 'getTotalDonations':
            echo json_encode(["Total Donations: $500"]);
            break;
        case 'getEventStatus':
            echo json_encode(["Event 1: Active", "Event 2: Completed"]);
            break;
        default:
            echo json_encode(["Unknown action"]);
    }
}
?>
