<?php

require_once 'TravelPlan.php';

class TravelPlanCollection {
    private array $travelPlans = [];

    public function addTravelPlan(TravelPlan $travelPlan): void {
        $this->travelPlans[] = $travelPlan;
    }

    public function getTravelPlans(): array {
        return $this->travelPlans;
    }
}
?>
