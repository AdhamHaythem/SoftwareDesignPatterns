<?php

require_once 'DonorModel.php'; 
require_once 'IEvent.php';
require_once 'cash.php';


function main() {
 //   $event = new Event(new DateTime(), "Central Park",20, 10, 1);
    $startDate = new DateTime('2024-12-31 00:00:00');
    $paymentMethod1 = new Cash(); 
    $paymentMethod2 = new Cash(); 
    $event = new CampaignStrategy(
        100, $startDate, "", 9, 100, 
        "", 1000, "", 100000
    );

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
        eventStrategy: $event,
        eventData: $event
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
        eventStrategy: $event,
        eventData: $event
    );

    $event->registerObserver($donor1);
    $event->registerObserver($donor2);

    echo "Both donors have been registered as observers to the event.\n";
    $event->setStatus("The event has been postponed due to weather conditions!");

    echo "Both donors have been registered as observers to the event.\n";
    $event->setStatus("The event has been postponed due to 3'aba2!");

}

main();


?>