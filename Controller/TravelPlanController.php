<?php

require_once 'TravelPlan.php';

class TravelPlanController {
    public function createTravelPlan(array $data): bool {
        $travelPlan = new TravelPlan(
            $data['userID'],
            $data['eventID'],
            $data['destination'],
            $data['startDate'],
            $data['endDate'],
            $data['transportMode'],
            $data['cost']
        );
        return $travelPlan->save();
    }

    public function getTravelPlan(int $travelPlanID): ?TravelPlan {
        return TravelPlan::retrieve($travelPlanID);
    }

    public function deleteTravelPlan(int $travelPlanID): bool {
        return TravelPlan::delete($travelPlanID);
    }
}

$controller = new TravelPlanController();
if(isset($_POST['createTravelPlan'])) {
    if (!empty($_POST['userId']) && !empty($_POST['eventID']) && !empty($_POST['destination']) && !empty($_POST['startDate'])
    && !empty($_POST['endDate']) && !empty($_POST['transportMode']) && !empty($_POST['cost']))
    {    
    $data = [
        'userID' => $_POST['userID'],
        'eventID' => $_POST['eventID'],
        'destination' => $_POST['destination'],
        'startDate' => $_POST['startDate'],
        'endDate' => $_POST['endDate'],
        'transportMode' => $_POST['transportMode'],
        'cost' => $_POST['cost']
    ];
    $controller->createTravelPlan($data);
    }
}


if(isset($_POST['getTravelPlan']))
    $controller->getTravelPlan($_POST['travelPlanID']);

if(isset($_POST['deleteTravelPlan']))
    $controller->deleteTravelPlan($_POST['travelPlanID']);
?>
