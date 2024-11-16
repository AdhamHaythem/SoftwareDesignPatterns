<?php

require_once 'DonorModel.php'; 
require_once 'IEvent.php';


function main() {
    $event = new Event(new DateTime(), "Central Park",20, 10, 1);

    $paymentMethod1 = new IPaymentStrategy(); 
    $paymentMethod2 = new IPaymentStrategy(); 
 
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
        eventData: $event
    );

    $event->registerObserver($donor1);
    $event->registerObserver($donor2);

    echo "Both donors have been registered as observers to the event.\n";
    $event->setStatus("The event has been postponed due to weather conditions!");

}

main();

?>