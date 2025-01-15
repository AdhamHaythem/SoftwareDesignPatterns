<?php

// require_once '../Model/EventModel.php';
require_once '../Model/IEvent.php';
require_once '../Model/VolunteeringEventModel.php';
require_once '../Model/CampaignStrategy.php';
require_once '../View/EventView.php';

class EventController {

    public function getVolunteerInfo(int $donorId, $eventId): void {
        $eventView = new EventView();
        $eventModel = VolunteeringEventStrategy::retrieve($eventId);
        $donor = Donor::retrieve($donorId);
        $volunteer = $eventModel->getVolunteerInfo($donor);
        // $eventView->displayVolunteerInfo($volunteer);
    }

    public function addFunds(float $amount, int $eventId): bool {
        $event = CampaignStrategy::retrieve($eventId);
        if ($event) {
            $event->addFunds($amount);
            return true;
        }
        return false;
    }

    public function addVolunteerForCampaign(int $donorId, int $eventId): bool {
        $event = CampaignStrategy::retrieve($eventId);
        if ($event) {
            $event->addVolunteer($donorId);
            return true;
        }
        return false;
    }

    public function addVolunteerForVolunteeringEvent(int $donorId, int $eventId): bool {
        $event = VolunteeringEventStrategy::retrieve($eventId);
        if ($event) {
            $event->addVolunteer($donorId);
            return true;
        }
        return false;
    }

    public function createCampaign(CampaignStrategy $object) {
        $campaign = CampaignStrategy::create($object);
        return $campaign;
    }

    public function createVolunteeringEvent($object) {
        $event = VolunteeringEventStrategy::create($object);
        return $event;
    }

    public function retrieveCampaign(int $key): ?Event {
        return CampaignStrategy::retrieve($key);
    }

    public function retrieveVolunteeringEvent(int $key): ?Event {
        return VolunteeringEventStrategy::retrieve($key);
    }

    public function updateCampaign($updates): bool {
        if ($updates) {
            CampaignStrategy::update($updates); 
            return true;
        }
        return false;
    }

    public function updateVolunteeringEvent(VolunteeringEventStrategy $updates): bool {
        if ($updates) {
            VolunteeringEventStrategy::update($updates); 
            return true;
        }
        return false;
    }

    public function deleteCampaign(int $key): bool {
        return CampaignStrategy::delete($key);
    }

    public function deleteVolunteeringEvent(int $key): bool {
        return VolunteeringEventStrategy::delete($key);
    }

    public function undoEventJoin(int $Id)
    {
        $eventUndoCommand = new EventUndoCommand();
        $donor = Donor::retrieve($Id);
        $donor->setCommand($eventUndoCommand);
        $donor->undo();
    }

    public function redoEventJoin(int $Id)
    {
        $eventRedoCommand = new EventRedoCommand();
        $donor = Donor::retrieve($Id);
        $donor->setCommand($eventRedoCommand);
        $donor->redo();
    }

}

$eventController = new EventController();

if (isset($_POST['getVolunteerInfo'])) {
    if (!empty($_POST['donorId'])) {
        $eventController->getVolunteerInfo((int)$_POST['donorId'], (int)$_POST['eventId']);
        echo json_encode(['success' => true]);
    }
    exit;
}

if (isset($_POST['addVolunteerForCampaign'])) {
    if (!empty($_POST['donorId']) && !empty($_POST['eventId'])) {
        $result = $eventController->addVolunteerForCampaign((int)$_POST['donorId'], (int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}

if (isset($_POST['addVolunteerForVolunteeringEvent'])) {
    if (!empty($_POST['donorId']) && !empty($_POST['eventId'])) {
        $result = $eventController->addVolunteerForVolunteeringEvent((int)$_POST['donorId'], (int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}

if (isset($_POST['addFunds'])) {
    if (!empty($_POST['amount']) && !empty($_POST['eventId'])) {
        $result = $eventController->addFunds((float)$_POST['amount'], (int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}

if (isset($_POST['createEvent'])) {
    if (isset($_POST['Campaign'])) {
        if (!empty($_POST['eventId']) && !empty($_POST['eventName']) && !empty($_POST['volunteers_needed'])
            && !empty($_POST['location']) && !empty($_POST['time']) && !empty($_POST['target'])) {

            $object = new CampaignStrategy($_POST['eventId'],$_POST['time'],$_POST['location'],$_POST['volunteers_needed'],$_POST['eventId'],$_POST['eventName'],$_POST['target'],$_POST['eventName'],0);
            $result = $eventController->createCampaign($object);
            echo json_encode(['success' => !empty($result)]);
        }

    } elseif (isset($_POST['VolunteeringEvent'])) {
        if (!empty($_POST['eventId']) && !empty($_POST['eventName']) && !empty($_POST['volunteers_needed'])
            && !empty($_POST['location']) && !empty($_POST['time'])) {
            $object = new VolunteeringEventStrategy($_Post['eventName'],$_Post['time'],$_Post['location'],$_Post['volunteers_needed'],$_Post['eventId']);
            $result = $eventController->createVolunteeringEvent($object);
            echo json_encode(['success' => !empty($result)]);
        }
    }
    exit;
}

if (isset($_POST['retrieveCampaign'])) {
    if (!empty($_POST['eventId'])) {
        $event = $eventController->retrieveCampaign((int)$_POST['eventId']);
        if ($event) {
            $eventView = new EventView();
            $eventView->displayEventReport($event);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Event not found']);
        }
    }
    exit;
}

if (isset($_POST['retrieveVolunteeringEvent'])) {
    if (!empty($_POST['eventId'])) {
        $event = $eventController->retrieveVolunteeringEvent((int)$_POST['eventId']);
        if ($event) {
            $eventView = new EventView();
            $eventView->displayEventReport($event);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Event not found']);
        }
    }
    exit;
}

if (isset($_POST['updateEvent'])) {
    $updates = [];
    $event = Event::retrieve($updates['eventId']);

    if (!empty($_POST['eventId'])) {
        if (isset($_POST['eventName'])) {
            $event->setName($_POST['eventName']);
        }
        if (isset($_POST['volunteers_needed'])) {
            $event->setVolunteersNeeded($_POST['volunteers_needed']);
        }
        if (isset($_POST['location'])) {
            $event->setLocation($_POST['location']);
        }
        if (isset($_POST['time'])) {
            $event->setTime($_POST['time']);
        }

        if (isset($_POST['Campaign'])) {
            if (isset($_POST['target'])) {
                $event->setTarget($_POST['target']);
            }

            $result = $eventController->updateCampaign($event);
            echo json_encode(['success' => $result]);

        } elseif (isset($_POST['VolunteeringEvent'])) {
            $result = $eventController->updateVolunteeringEvent($event);
            echo json_encode(['success' => $result]);
        }
    }
    exit;
}

if (isset($_POST['deleteCampaign'])) {
    if (!empty($_POST['eventId'])) {
        $result = $eventController->deleteCampaign((int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}


if (isset($_POST['undoEvent'])) {
    if(!empty($_post['donorID']))
    {
        $eventController->undoEventJoin($_post['donorID']);
    }
}


if (isset($_POST['redoEvent'])) {
    if(!empty($_post['donorID']))
    {
        $eventController->redoEventJoin($_post['donorID']);
    }
}


if (isset($_POST['deleteVolunteeringEvent'])) {
    if (!empty($_POST['eventId'])) {
        $result = $eventController->deleteVolunteeringEvent((int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}
?>
