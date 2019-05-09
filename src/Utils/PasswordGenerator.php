<?php

namespace App\Utils;

class PasswordGenerator
{
    public function generate($length = 15) {
        $characters = 'azertyuiopmlkjhgfdsqwxcvbn0123456789NBVCXWMLKJHGFDSQAZERTYUIOP';
        $specialCharacters = '*-+.!:/;?%@&#';
        $password = '';

        for ($index = 0; $index <= $length; $index++) {
            if ($index % 2 == 0) {
                $password .= $characters[mt_rand(0, (strlen($characters) - 1))];
            } else {
                $password .= $specialCharacters[mt_rand(0, (strlen($specialCharacters) - 1))];
            }
        }
        return $password;
    }
}