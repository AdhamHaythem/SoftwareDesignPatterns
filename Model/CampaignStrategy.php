<?php

require_once 'IEvent.php';
require_once 'EventModel.php';

class CampaignStrategy implements IEvent {

    public function signUp(Event $event,int $donorID): bool {

        if (!isset($donorID)) {
            return false;
        }
        if (count($event->getVolunteersList()) < $event->getVolunteersNeeded()) {
            echo "Campaign SignUp: Donor $donorID is successfully signed up for the campaign event!\n";
            return $event->addVolunteer($donorID);
        }
        echo "Campaign SignUp: No more spots available for the campaign event.\n";
        return false;
    }

    public function getAllEvents(): array {
        echo "Fetching all campaign events...\n";
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
            return 'Campaign Event is still open for more volunteers';
        }
        return 'Campaign Event is fully booked';
    }

    public function generateEventReport(Event $event): string {
        $report = "Campaign Event Report for Event ID: " . $event->getEventID() . "\n";
        $report .= "Location: " . $event->getLocation() . "\n";
        $report .= "Volunteers Needed: " . $event->getVolunteersNeeded() . "\n";
        $report .= "Current Volunteers: " . count($event->getVolunteersList()) . "\n";
        $report .= "Campaign Event Status: " . $this->checkEventStatus($event) . "\n";
        return $report;
    }

    public function sendReminderToVolunteers(Event $event): void {
        foreach ($event->getVolunteersList() as $volunteer) {
            echo "Reminder sent to Volunteer ID: " . $volunteer . " for the campaign event.\n";
        }
    }
}

?>
