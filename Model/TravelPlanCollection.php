<?php

require_once 'TravelPlanIterator.php';

class TravelPlanCollection {
    private array $travelPlans = [];

    // Add a travel plan to the collection
    public function addTravelPlan(TravelPlan $travelPlan): void {
        $this->travelPlans[] = $travelPlan;
    }

    // Get the iterator for the collection
    public function getIterator(): TravelPlanIterator {
        return new TravelPlanIterator($this->travelPlans);
    }

    // Clear all travel plans from the collection
    public function clear(): void {
        $this->travelPlans = [];
    }
}
?>
