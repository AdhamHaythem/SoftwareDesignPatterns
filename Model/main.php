<?php

require_once 'DonorModel.php'; 
require_once 'IEvent.php';
require_once 'cash.php';
require_once 'VolunteeringEventModel.php';

function printCampaigns(Donor $donor): void {
    if (empty($donor->getEvents())) {
        echo "No campaigns joined.\n";
        return;
    }

    echo "List of campaigns joined:\n";
    foreach ($donor->getEvents() as $campaign) {
        // Assuming the $campaign object has a __toString() method or properties to display
        echo $campaign->getName() . "\n"; // Replace with specific campaign details if needed
    }
}

function main() {
 //   $event = new Event(new DateTime(), "Central Park",20, 10, 1);
    $startDate = new DateTime('2024-12-31 00:00:00');
    $FayoomDate = new DateTime('2024-11-30 08:00:00');
    $paymentMethod1 = new Cash(); 
    $paymentMethod2 = new Cash(); 
    $event = new CampaignStrategy(
        100, $startDate, "", 9, 100, 
        "Sha3boly", 1000, "", 100000
    );

    $newEvent= new VolunteeringEventStrategy(name: "Fayoom", time: $FayoomDate, location: "Fayoom,Asyout", volunteersNeeded: 9, eventID: 100);
    $donor1 = new Donor(
        userID: 1,
        username: "donor1",
        firstname: "Alice",
        lastname: "Smith",
        email: "alice.smith@example.com",
        password: "password123",
        location: ["city" => "New York", "country" => "USA"],
        phoneNumber: 1234567890,
        paymentMethod: $paymentMethod1,
        event:  $event
    );

    $donor2 = new Donor(
        userID: 2,
        username: "donor2",
        firstname: "Bob",
        lastname: "Johnson",
        email: "bob.johnson@example.com",
        password: "password456",
        location: ["city" => "Los Angeles", "country" => "USA"],
        phoneNumber: 9876543210,
        paymentMethod: $paymentMethod2,
    );


    $newEvent->registerObserver($donor1);
    $newEvent->registerObserver($donor2);

    // echo "Both donors have been registered as observers to the event.\n";
    // $event->setStatus("The event has been postponed due to to2l damo!");
    echo"First Additions \n";

    echo "Donor 1 \n";
    
    printCampaigns($donor1);

    echo "Donor 2 \n";
    printCampaigns($donor2);
    
    echo"Elfayoom Event\n";
    // $newEvent->setStatus("Msh tal33eeen fayoom due to to2l dam eldban!");


    $donor2->addEvent($newEvent);
    // echo "Both donors have been registered as observers to the event.\n";
    // $event->setStatus("The event has been postponed due to 3'aba2!");
    echo"Second Additions \n";

    echo "Donor 1 \n";
    
    printCampaigns($donor1);

    echo "Donor 2 \n";
    printCampaigns($donor2);
}

main();


?>