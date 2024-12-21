<?php
class MapController {
    private $healthModel;
    private $disasterModel;

    public function __construct($db) {
        $this->healthModel = new Health($db);
        $this->disasterModel = new Disaster($db);
    }

    public function index() {
        $healthData = $this->healthModel->getAllHealthData();
        $disasterData = $this->disasterModel->getAllDisasterData();
        $floodAlerts = $this->disasterModel->getFloodAlertAreas();

        include '../views/maps/index.php';
    }
}
?>
