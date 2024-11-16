<?php
require_once "../View/AdminView.php";
class TechnicalController {
    
    public function getReports($technical) {
        $reports = $technical->getReports();
        $x = new AdminView();
        $x->displayReports($reports);
        
    }

}

 // TechnicalController actions
    $technicalController = new TechnicalController();
    if (isset($_POST['getReports'])) {
        $technicalController->getReports($_post['techical']);
    } 
?>
