<?php
require_once 'ICommand.php';
require_once 'DonorModel.php';
require_once 'IEvent.php';

class EventRedoCommand implements ICommand {
    private ?Donor $donor = null;
    private ?Event $event = null;

    public function __construct() {}

    public function execute(): void {
        echo "Executing EventRedoCommand\n";

        if ($this->donor === null || $this->event === null) {
            echo "Redo failed: No donor or event set.\n";
            return;
        }

        // Add the event back to the donor's joined events
        $this->donor->addEvent($this->event);
        echo "Redo successful: Event '{$this->event->getName()}' added back to donor.\n";
    }

    public function setDonor(Donor $donor): void {
        $this->donor = $donor;
    }

    public function setEvent(Event $event): void {
        $this->event = $event;
    }
}