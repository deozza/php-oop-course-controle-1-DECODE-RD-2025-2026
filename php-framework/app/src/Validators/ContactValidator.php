<?php

namespace App\Validators;

class ContactValidator{
    private const ALLOWED_FIELDS = ['email', 'subject', 'message'];

    public function isBodyValid(array $body): bool{
        $nonExpectedFields = array_diff(array_keys($body), self::ALLOWED_FIELDS);

        if (!empty($nonExpectedFields)) {
            return false;
        }
        return true;
    }



}