<?php

interface IEvent {
    public function signUp(Event $event ,int $donorID): bool;
    public function getAllEvents(): array;
    public function processEvents(): void;
    public function checkEventStatus(Event $event): string;
    public function generateEventReport(Event $event): string;
    public function sendReminderToVolunteers(Event $event): void;
}

?>
