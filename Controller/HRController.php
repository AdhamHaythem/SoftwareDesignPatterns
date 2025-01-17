<?php
require_once "../View/AdminView.php";
class HRController {
    
    public function getReports($hr,$id) {
        $reports = $hr->getReports();
        $x = new AdminView();
        $proxy= new ReportsGenerationProxy("hr",new ReportGenerator());
        $results= [];
        $finalizedReports= $proxy->finalizeReport($id,$results);
        // $x->displayReports($reports);
        
    }

}

 // HRController actions
    $hrController = new HRController();
    if (isset($_POST['getReports'])) {
        $hr = HRModel::retrieve($_POST['userId']);
        $hrController->getReports($hr,$_POST['id']);
    } 
    
?>

