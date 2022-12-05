<?php

namespace App\Validator;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Custom Validotor class to appaend custom validate function to usual validator
 */
Abstract class BaseValidator
{
    private $validator;

    public function __construct( ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public  function validate(EntityInterface $entity)
    {
        $verrors = $this->validator->validate($entity);
        $errors = [];
        foreach ($verrors as $message) {
            $errors[] = $message->getPropertyPath() . ': ' . $message->getMessage();
        }

        return $errors;
    }
}