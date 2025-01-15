<?php
require_once "../View/AdminView.php";
class HRController {
    
    public function getReports($hr) {
        $reports = $hr->getReports();
        $x = new AdminView();
        $x->displayReports($reports);
        
    }

}

 // HRController actions
    $hrController = new HRController();
    if (isset($_POST['getReports'])) {
        $hr = HRModel::retrieve($_POST['userId']);
        $hrController->getReports($hr);
    } 
?>
