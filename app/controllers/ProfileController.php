<?php

require_once './app/models/User.php';

class ProfileController {

    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function show($userId) {
        try {
            $user = $this->user->show($userId);

            if ($user) {
                return json_encode([
                    'status' => 'success',
                    'data' => $user,
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'User not found',
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    public function storeProfile($userId, $bio, $file, $phoneNumber, $address) {
        try {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($file['type'], $allowedTypes)) {
                    return json_encode([
                        'status' => 'error',
                        'message' => 'Invalid file type. Only JPG, PNG, and GIF files are allowed.',
                    ]);
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = $this->generateFileName($userId, $extension);

                $uploadDir = __DIR__ . '/../../storage/';
                $filePath = $uploadDir . $fileName;
    
                if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                    return json_encode([
                        'status' => 'error',
                        'message' => 'Failed to save the uploaded file.',
                    ]);
                }

                $profileImage = '/storage/' . $fileName;
            } else {
                $profileImage = null; 
            }

            $success = $this->user->storeProfile($userId, $bio, $profileImage, $phoneNumber, $address);
    
            if ($success) {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Profile updated successfully',
                ]);
            } else {
                return json_encode([
                    'status' => 'error',
                    'message' => 'Failed to update profile',
                ]);
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return json_encode([
                'status' => 'error',
                'message' => 'Server error',
            ]);
        }
    }

    private function generateFileName($userId, $extension) {
        return 'profile_' . $userId . '_' . time() . '.' . $extension;
    }
}
