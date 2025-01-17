<?php
require_once 'ICommand.php';
require_once 'DonorModel.php';
require_once 'IEvent.php';

class EventUndoCommand implements ICommand {
    private ?Donor $donor = null;
    private ?Event $event = null;

    public function __construct() {}

    public function execute(): void {
        if ($this->donor === null || $this->event === null) {
            throw new Exception("Donor or event not set. Cannot execute undo command.");
            return;
        }

        // Remove the event from the donor's joined events
        $this->donor->removeEvent($this->event);
        throw new Exception("Event removed from donor's joined events.");
    }

    public function setDonor(Donor $donor): void {
        $this->donor = $donor;
    }

    public function setEvent(Event $event): void {
        $this->event = $event;
    }
}