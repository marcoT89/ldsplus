<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SameGender implements Rule
{
    private $gender;

    public function __construct($gender)
    {
        $this->gender = $gender;
    }

    public function passes($attribute, $object)
    {
        if (is_null($object) || is_null($this->gender)) {
            return true;
        }

        return $this->gender === 'both' ? true : $object->gender == $this->gender;
    }

    public function message()
    {
        return 'The :attribute gender is invalid.';
    }
}
