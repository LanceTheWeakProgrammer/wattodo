<?php

require_once './core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function store($username, $name, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->db->prepare("INSERT INTO users (username, name, password) VALUES (:username, :name, :password)");

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);

            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }
        catch (PDOException $e)
        {
            error_log($e->getMessage());
            return false;
        }
    }

    public function auth($username) {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function validate($username, $password) {

           $user = $this->auth($username);

           if ($user && password_verify($password, $user['password'])) {
            return true;
           }

        return false;
    }

    public function show($userId) {
        try {
            $stmt = $this->db->prepare('
                SELECT 
                    users.id AS user_id,
                    users.username,
                    users.name,
                    users.created_at AS user_created_at,
                    profiles.bio,
                    profiles.profile_image,
                    profiles.phone_number,
                    profiles.address,
                    profiles.created_at AS profile_created_at,
                    profiles.updated_at AS profile_updated_at
                FROM users
                LEFT JOIN profiles ON users.id = profiles.user_id
                WHERE users.id = :id
            ');

            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null; 
        }
    }

    public function storeProfile($userId, $bio, $profileImage, $phoneNumber, $address) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO profiles (user_id, bio, profile_image, phone_number, address)
                VALUES (:user_id, :bio, :profile_image, :phone_number, :address)
                ON DUPLICATE KEY UPDATE
                    bio = VALUES(bio),
                    profile_image = VALUES(profile_image),
                    phone_number = VALUES(phone_number),
                    address = VALUES(address)
            ");
    
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':bio', $bio, PDO::PARAM_STR);
            $stmt->bindParam(':profile_image', $profileImage, PDO::PARAM_STR);
            $stmt->bindParam(':phone_number', $phoneNumber, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}