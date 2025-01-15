<?php
require_once 'ICommand.php';
require_once 'DonorModel.php';
require_once 'IEvent.php';

class EventUndoCommand implements ICommand {
    private ?Donor $donor = null;
    private ?Event $event = null;

    public function __construct() {}

    public function execute(): void {
        echo "Executing EventUndoCommand\n";

        if ($this->donor === null || $this->event === null) {
            echo "Undo failed: No donor or event set.\n";
            return;
        }

        // Remove the event from the donor's joined events
        $this->donor->removeEvent($this->event);
        echo "Undo successful: Event '{$this->event->getName()}' removed from donor.\n";
    }

    public function setDonor(Donor $donor): void {
        $this->donor = $donor;
    }

    public function setEvent(Event $event): void {
        $this->event = $event;
    }
}