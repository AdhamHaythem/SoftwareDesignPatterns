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
?>
