<?php

require_once '../Model/delivery.php';
class DeliveryPersonnelController {

    public function updateDestination($deliveryId): void {
        $delivery= Delivery::retrieve($deliveryId);
        $destination=$delivery->getDestination();
        // $deliveryView=new DeliveryView();
        // $deliveryView->displayDestination($destination);
    }
}


$deliveryPersonnelController  = new DeliveryPersonnelController();
if (($_POST['updateDestination'])) {
        $deliveryPersonnelController->updateDestination($_POST['deliveryId']);
    }




?>
