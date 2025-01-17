<?php

require_once 'MyIterator.php';
require_once 'TravelPlan.php';

class TravelPlanIterator extends MyIterator {
    private array $travelPlans = [];

    public function __construct(array $travelPlans) {
        $this->travelPlans = $travelPlans;
        $this->rewind();
    }

    // Reset the position to the start
    public function rewind(): void {
        $this->position = 0;
    }

    // Return the current element
    public function current(): mixed {
        return $this->travelPlans[$this->position] ?? null;
    }

    // Return the key of the current element
    public function key(): int {
        return $this->position;
    }

    // Move forward to the next element
    public function next(): mixed {
        $this->position++;
        return $this->current();
    }

    // Check if the current position is valid
    public function valid(): bool {
        return isset($this->travelPlans[$this->position]);
    }

    // Clear all travel plans
    public function clear(): void {
        $this->travelPlans = [];
        $this->rewind();
    }
}
?>
