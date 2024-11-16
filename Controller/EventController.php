<?php

require_once '../Model/EventModel.php';
require_once '../Model/VolunteeringEventModel.php';
require_once '../Model/CampaignModel.php';
require_once '../View/EventView.php';

class EventController {

    public function getVolunteerInfo(int $donorId,$eventId): void {
        $eventView = new EventView();
        $eventModel = VolunteeringEvent::retrieve($eventId);
        $donor = Donor::retrieve($donorId);
        $volunteer = $eventModel->getVolunteerInfo($donor);
        $eventView->displayVolunteerInfo($volunteer);
    }

    public function addFunds(float $amount, int $eventId): bool {
        $event = CampaignModel::retrieve($eventId);
        if ($event) {
            $event->addFunds($amount);
            return true;
        }
        return false;
    }

    public function addVolunteerForCampaign(int $donorId, int $eventId): bool {
        $event = CampaignModel::retrieve($eventId);
        if ($event) {
            $event->addVolunteer($donorId);
            return true;
        }
        return false;
    }



    public function addVolunteerForVolunteeringEvent(int $donorId, int $eventId): bool {
        $event = VolunteeringEvent::retrieve($eventId);
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
        $event = VolunteeringEvent::create(
            $object['eventId'],
            $object['eventName'],
            $object['volunteers_needed'],
            $object['location'],
            $object['time']
        );
        return $event;
    }

    public function retrieveCampaign(int $key): ?Event {
        return CampaignModel::retrieve($key);
    }

    public function retrieveVolunteeringEvent(int $key): ?Event {
        return VolunteeringEvent::retrieve($key);
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
        $volunteeringEventModel = VolunteeringEvent::retrieve($updates['eventId']);
        if ($volunteeringEventModel) {
            $volunteeringEventModel->updateVolunteeringEvent($updates); 
            return true;
        }
        return false;
    }
    
    public function deleteCampaign(int $key): bool {
        return CampaignModel::delete($key);
    }


    public function deleteVolunteeringEvent(int $key): bool {
        return VolunteeringEvent::delete($key);
    }


}

$eventController = new EventController();

if (isset($_POST['getVolunteerInfo'])) {
    if (!empty($_POST['donorId'])) {
        $eventController->getVolunteerInfo((int)$_POST['donorId'],(int)$_Post['eventId']);
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

if (isset($_POST['deleteCampaign'])) {
    if (!empty($_POST['eventId'])) {
        $result = $eventController->deleteCampaign((int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}



if (isset($_POST['deleteVolunteeringEvent'])) {
    if (!empty($_POST['eventId'])) {
        $result = $eventController->deleteVolunteeringEvent((int)$_POST['eventId']);
        echo json_encode(['success' => $result]);
    }
    exit;
}


?>
