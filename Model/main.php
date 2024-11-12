<?php
require_once 'IEvent.php';
require_once 'EventModel.php';
require_once 'CampaignStrategy.php';
require_once 'VolunteeringEventStrategy.php';

function main() {

    $eventID = 1; 
    $eventTime = new DateTime('2024-12-01 10:00:00'); 
    $location = "City Park"; 
    $volunteersNeeded = 5; 

    $campaignEvent = new Event($eventTime, $location, $volunteersNeeded, $eventID, new CampaignStrategy()); 
    $volunteeringEvent = new Event($eventTime, $location, $volunteersNeeded, $eventID, new VolunteeringEventStrategy()); 
    $donorID = 101;

    echo "---- Testing CampaignStrategy ----\n";
    $campaignEvent->signUpBasedOnStrategy($donorID);
    echo "----------------------------------\n";

    echo "---- Changing to VolunteeringEventStrategy ----\n";
    $campaignEvent->setStrategy(new VolunteeringEventStrategy());
    $campaignEvent->signUpBasedOnStrategy($donorID); 
    echo "-------------------------------------------\n";
}

main();
?>
