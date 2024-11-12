<?php

require_once 'IEvent.php';
require_once 'Event.php';

class VolunteeringEventStrategy implements IEvent {


    public function signUp(Event $event , int $donorID): bool {
        if (!isset($donorID)) {
            echo "Volunteering SignUp: Donor ID is not provided.\n";
            return false;
        }
        
        if (count($event->getVolunteersList()) < $event->getVolunteersNeeded()) {
            echo "Volunteering SignUp: Donor $donorID successfully signed up to volunteer for the event.\n";
            return $event->addVolunteer($donorID);
        }
        echo "Volunteering SignUp: No more spots available for volunteers.\n";
        return false;
    }

    public function getAllEvents(): array {
        echo "Fetching all volunteering events...\n";
        return Event::getAll();
    }
    public function processEvents(): void {
        $events = $this->getAllEvents();
        foreach ($events as $event) {
            if ($event->getTime() > new DateTime()) {
                $this->sendReminderToVolunteers($event);
            }
        }
    }

    public function checkEventStatus(Event $event): string {
        if ($event->getVolunteersNeeded() > count($event->getVolunteersList())) {
            return 'Volunteering Event is open for more volunteers';
        }
        return 'Volunteering Event is fully booked';
    }

    public function generateEventReport(Event $event): string {
        $report = "Volunteering Event Report for Event ID: " . $event->getEventID() . "\n";
        $report .= "Location: " . $event->getLocation() . "\n";
        $report .= "Volunteers Needed: " . $event->getVolunteersNeeded() . "\n";
        $report .= "Current Volunteers: " . count($event->getVolunteersList()) . "\n";
        $report .= "Volunteering Event Status: " . $this->checkEventStatus($event) . "\n";
        return $report;
    }
    public function sendReminderToVolunteers(Event $event): void {
        foreach ($event->getVolunteersList() as $volunteer) {
            echo "Reminder sent to Volunteer ID: " . $volunteer . " for the volunteering event.\n";
        }
    }
}

?>
