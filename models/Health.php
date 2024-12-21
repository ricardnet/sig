<?php

class Health {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllHealthData() {
        try {
            $query = "SELECT * FROM health_records ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function createHealthRecord($data) {
        try {
            $query = "INSERT INTO health_records 
                    (citizen_nik, citizen_name, health_condition, status, 
                     health_description, latitude, longitude, recorded_by) 
                    VALUES 
                    (:nik, :name, :health_condition, :status, 
                     :health_description, :latitude, :longitude, :recorded_by)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':nik' => $data['citizen_nik'],
                ':name' => $data['citizen_name'],
                ':health_condition' => $data['health_condition'],
                ':status' => $data['status'],
                ':health_description' => $data['health_description'],
                ':latitude' => $data['latitude'],
                ':longitude' => $data['longitude'],
                ':recorded_by' => $_SESSION['username'] ?? 'System'
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getHealthById($id) {
        try {
            $query = "SELECT * FROM health_records WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateHealthRecord($id, $data) {
        try {
            $query = "UPDATE health_records SET 
                    citizen_nik = :nik,
                    citizen_name = :name,
                    health_condition = :condition,
                    status = :status,
                    health_description = :health_description,
                    latitude = :latitude,
                    longitude = :longitude
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':nik' => $data['citizen_nik'],
                ':name' => $data['citizen_name'],
                ':condition' => $data['health_condition'],
                ':status' => $data['status'],
                ':health_description' => $data['health_description'],
                ':latitude' => $data['latitude'],
                ':longitude' => $data['longitude']
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteHealthRecord($id) {
        try {
            $query = "DELETE FROM health_records WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
?>
