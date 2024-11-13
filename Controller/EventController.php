<?php

require_once '../Model/EventModel.php';
require_once '../Model/VolunteeringEventModel.php';
require_once '../Model/CampaignModel.php';
require_once '../View/EventView.php';

class EventController {

    public function getVolunteerInfo(int $donorId): void {
        $eventView = new EventView();
        $eventModel = new EventModel();

        $volunteer = $eventModel->getVolunteerInfo($donorId);
        $eventView->displayVolunteerInfo($volunteer);
    }

    public function addFunds(float $amount, int $eventId): bool {
        $event = $this->retrieve($eventId);
        if ($event) {
            $event->addFunds($amount);
            return true;
        }
        return false;
    }

    public function addVolunteer(int $donorId, int $eventId): bool {
        $event = $this->retrieve($eventId);
        if ($event) {
            $event->addVolunteer($donorId);
            return true;
        }
        return false;
    }

    public function createCampaign(array $object) {
        $campaign = CampaignModel::create(
            $object['eventId'],
            $object['eventName'],
            $object['volunteers_needed'],
            $object['location'],
            $object['time'],
            $object['target']
        );
        return $campaign;
    }

    public function createVolunteeringEvent(array $object) {
        $event = VolunteeringEventModel::create(
            $object['eventId'],
            $object['eventName'],
            $object['volunteers_needed'],
            $object['location'],
            $object['time']
        );
        return $event;
    }

    public function retrieve(int $key): ?EventModel {
        return EventModel::retrieve($key);
    }

    public function updateCampaign(array $updates): bool {
        $campaignModel = CampaignModel::retrieve($updates['eventId']);
        if ($campaignModel) {
            $campaignModel->updateCampaign($updates); 
            return true;
        }
        return false;
    }
    
    public function updateVolunteeringEvent(array $updates): bool {
        $volunteeringEventModel = VolunteeringEventModel::retrieve($updates['eventId']);
        if ($volunteeringEventModel) {
            $volunteeringEventModel->updateVolunteeringEvent($updates); 
            return true;
        }
        return false;
    }
    
    public function delete(int $key): bool {
        return EventModel::delete($key);
    }
}

$eventController = new EventController();

if (isset($_POST['getVolunteerInfo'])) {
    if (!empty($_POST['donorId'])) {
        $eventController->getVolunteerInfo((int)$_POST['donorId']);
        echo json_encode(['success' => true]);
    } 
    exit;
}

if (isset($_POST['addVolunteer'])) {
    if (!empty($_POST['donorId']) && !empty($_POST['eventId'])) {
        $result = $eventController->addVolunteer((int)$_POST['donorId'], (int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    } 
    exit;
}

if (isset($_POST['assignToEvent'])) {
    if (!empty($_POST['donorId']) && !empty($_POST['eventId'])) {
        $result = $eventController->addVolunteer((int)$_POST['donorId'], (int)$_POST['eventId']);
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

            $object = [
                'eventId' => $_POST['eventId'],
                'eventName' => $_POST['eventName'],
                'volunteers_needed' => $_POST['volunteers_needed'],
                'location' => $_POST['location'],
                'time' => $_POST['time'],
                'target' => $_POST['target']
            ];

            $result = $eventController->createCampaign($object);
            echo json_encode(['success' => !empty($result)]);
        } 

    } elseif (isset($_POST['VolunteeringEvent'])) {
        if (!empty($_POST['eventId']) && !empty($_POST['eventName']) && !empty($_POST['volunteers_needed'])
            && !empty($_POST['location']) && !empty($_POST['time'])) {

            $object = [
                'eventId' => $_POST['eventId'],
                'eventName' => $_POST['eventName'],
                'volunteers_needed' => $_POST['volunteers_needed'],
                'location' => $_POST['location'],
                'time' => $_POST['time']
            ];

            $result = $eventController->createVolunteeringEvent($object);
            echo json_encode(['success' => !empty($result)]);
        } 
    }
    exit;
}

if (isset($_POST['retrieveEvent'])) {
    if (!empty($_POST['eventId'])) {
        $event = $eventController->retrieve((int)$_POST['eventId']);
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

    if (!empty($_POST['eventId'])) {
        if (isset($_POST['eventName'])) {
            $updates['eventName'] = $_POST['eventName'];
        }
        if (isset($_POST['volunteers_needed'])) {
            $updates['volunteers_needed'] = $_POST['volunteers_needed'];
        }
        if (isset($_POST['location'])) {
            $updates['location'] = $_POST['location'];
        }
        if (isset($_POST['time'])) {
            $updates['time'] = $_POST['time'];
        }

        if (isset($_POST['Campaign'])) {
            if (isset($_POST['target'])) {
                $updates['target'] = $_POST['target'];
            }

            if (!empty($updates)) {
                $result = $eventController->updateCampaign(array_merge(['eventId' => $_POST['eventId']], $updates));
                echo json_encode(['success' => $result]);
            }

        } elseif (isset($_POST['VolunteeringEvent'])) {
            if (!empty($updates)) {
                $result = $eventController->updateVolunteeringEvent(array_merge(['eventId' => $_POST['eventId']], $updates));
                echo json_encode(['success' => $result]);
            }
        }
    }
    exit;
}

if (isset($_POST['deleteEvent'])) {
    if (!empty($_POST['eventId'])) {
        $result = $eventController->delete((int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}

?>
