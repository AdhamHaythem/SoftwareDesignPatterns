<?php

require_once '../Model/delivery.php';
class DeliveryPersonnelController {

    // Method to schedule delivery
    // public function scheduleDelivery($params) {
    //     // Logic to schedule a delivery
    //     return $params; // Return the scheduled delivery info or status
    // }

    // // Method to track deliveries
    // public function trackDeliveries($params) {
    //     // Logic to track deliveries
    //     return $params; // Return delivery tracking info or status
    // }

    public function updateDestination($deliveryId): void {
        $delivery= Delivery::retrieve($deliveryId);
        $destination=$delivery->getDestination();
        $deliveryView=new DeliveryView();
        $deliveryView->displayDestination($destination);
    }
}


// DeliveryPersonnelController actions
    $deliveryPersonnelController = new DeliveryPersonnelController();
    if ($_POST['action'] === 'scheduleDelivery' && isset($_POST['params'])) {
        // $deliveryPersonnelController->scheduleDelivery($_POST['params']);
    } elseif ($_POST['action'] === 'trackDeliveries' && isset($_POST['params'])) {
        //$deliveryPersonnelController->trackDeliveries($_POST['params']);
    } elseif ($_POST['action'] === 'updateDestination') {
        $deliveryPersonnelController->updateDestination($_POST['deliveryId']);
    }




?>
