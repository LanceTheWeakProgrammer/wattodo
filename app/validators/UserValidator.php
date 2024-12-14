<?php

class UserValidator {
    public static function validate($data) {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = "Username is required.";
        } elseif (strlen($data['username']) < 3) {
            $errors[] = "Username must be at least 3 characters.";
        }

        if (empty($data['name'])) {
            $errors[] = "Name is required.";
        }

        if (empty($data['password'])) {
            $errors[] = "Password is required.";
        } elseif (strlen($data['password']) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }

        return $errors;
    }
}
