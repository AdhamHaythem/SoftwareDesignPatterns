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
        $tech = technicalModel::retrieve($_Post['userId']);
        $technicalController->getReports($tech);
    } 
?>
