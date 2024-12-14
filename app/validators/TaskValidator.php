<?php

class TaskValidator {
    public static function validate($data) {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = "Title is required.";
        }

        if (empty($data['description'])) {
            $errors[] = "Description is required.";
        }

        if (empty($data['category_id']) || !is_numeric($data['category_id'])) {
            $errors[] = "A valid category is required.";
        }

        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            if (new DateTime($data['start_date']) > new DateTime($data['end_date'])) {
                $errors[] = "Start date cannot be later than end date.";
            }
        }

        return $errors;
    }
}
