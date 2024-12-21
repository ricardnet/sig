<?php

class Disaster {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllDisasterData() {
        try {
            $query = "SELECT * FROM disaster_records ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function createDisasterRecord($data) {
        try {
            $query = "INSERT INTO disaster_records 
                    (disaster_type, severity_level, location, status, 
                     description, latitude, longitude) 
                    VALUES 
                    (:type, :severity, :location, :status, 
                     :description, :latitude, :longitude)";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':type' => $data['disaster_type'],
                ':severity' => $data['severity_level'],
                ':location' => $data['location'],
                ':status' => $data['status'],
                ':description' => $data['description'],
                ':latitude' => $data['latitude'],
                ':longitude' => $data['longitude'],
                // ':reporter_name' => $_SESSION['username'] ?? 'System'
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getDisasterById($id) {
        try {
            $query = "SELECT * FROM disaster_records WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateDisasterRecord($id, $data) {
        try {
            $query = "UPDATE disaster_records SET 
                    disaster_type = :type,
                    severity_level = :severity,
                    location = :location,
                    status = :status,
                    description = :description,
                    latitude = :latitude,
                    longitude = :longitude
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':type' => $data['disaster_type'],
                ':severity' => $data['severity_level'],
                ':location' => $data['location'],
                ':status' => $data['status'],
                ':description' => $data['description'],
                ':latitude' => $data['latitude'],
                ':longitude' => $data['longitude']
            ]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteDisasterRecord($id) {
        try {
            $query = "DELETE FROM disaster_records WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':id' => $id]);
            
            return true;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
?>