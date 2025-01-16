<?php
require_once 'Model/ReportsGenerationTemplate.php';
class ReportsGenerationProxy {
    protected ReportsGenerationTemplate $instance;
    protected bool $isAccessible;
    public function __construct($role,$instance){
        if($role=="admin"||$role=="hr" || $role=="DonationManager"){
            $this->instance = $instance;
            $this->isAccessible = true;
        }
    }
        final public function finalizeReport(int $userID,array $results): array {
        if($this->isAccessible){
            return $this->instance->finalizeReport($userID,$results);
        }
        else{
            return [];
        }
    }
}
?> 