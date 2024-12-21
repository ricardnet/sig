<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($userData) {
        try {
            $query = "INSERT INTO users (username, password, full_name, email, phone_number, address, role) 
                     VALUES (:username, :password, :full_name, :email, :phone_number, :address, :role)";
            
            $stmt = $this->db->prepare($query);
            
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':password', $userData['password']);
            $stmt->bindParam(':full_name', $userData['full_name']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':phone_number', $userData['phone_number']);
            $stmt->bindParam(':address', $userData['address']);
            $stmt->bindParam(':role', $userData['role']);
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                throw new Exception("Username atau email sudah digunakan");
            }
            throw new Exception($e->getMessage());
        }
    }

    public function getUserByUsername($username) {
        try {
            $query = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateUser($id, $userData) {
        try {
            $updateFields = [];
            $params = [':id' => $id];

            // Dynamically build update query based on provided data
            foreach ($userData as $key => $value) {
                if (!empty($value)) {
                    $updateFields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }

            if (empty($updateFields)) {
                return false;
            }

            $query = "UPDATE users SET " . implode(', ', $updateFields) . 
                     ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                throw new Exception("Email sudah digunakan oleh pengguna lain");
            }
            throw new Exception($e->getMessage());
        }
    }

    public function deleteUser($id) {
        try {
            $query = "DELETE FROM users WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getAllUsers() {
        try {
            $query = "SELECT id, username, full_name, email, phone_number, address, role, created_at 
                     FROM users ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function changePassword($id, $newPassword) {
        try {
            $query = "UPDATE users SET password = :password, 
                     updated_at = CURRENT_TIMESTAMP WHERE id = :id";
            
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getUsersByRole($role) {
        try {
            $query = "SELECT id, username, full_name, email, phone_number, address, created_at 
                     FROM users WHERE role = :role ORDER BY created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
?>
