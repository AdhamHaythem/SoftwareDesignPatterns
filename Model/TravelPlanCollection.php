<?php

require_once 'TravelPlan.php';
require_once 'TravelPlanIteratorInterface.php';

class TravelPlanCollection {
    private array $travelPlans = [];

    public function addTravelPlan(TravelPlan $travelPlan): void {
        $this->travelPlans[] = $travelPlan;
    }

    public function getIterator(): TravelPlanIterator {
        return new TravelPlanIterator($this->travelPlans);
    }
}

class TravelPlanIterator implements TravelPlanIteratorInterface {
    private array $travelPlans;
    private int $position = 0;

    public function __construct(array $travelPlans) {
        $this->travelPlans = $travelPlans;
    }

    public function hasNext(): bool {
        return $this->position < count($this->travelPlans);
    }

    public function next(): ?TravelPlan {
        if (!$this->hasNext()) {
            return null;
        }
        return $this->travelPlans[$this->position++];
    }
}
?>

