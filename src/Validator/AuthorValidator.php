<?php

namespace App\Validator;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorValidator
{
    private $authorRepository, $validator;

    public function __construct(AuthorRepository $authorRepository, ValidatorInterface $validator)
    {
        $this->authorRepository = $authorRepository;
        $this->validator = $validator;
    }

    public  function validate(Author $author)
    {
        $verrors = $this->validator->validate($author);
        $errors = [];
        foreach ($verrors as $message) {
            $errors[] = $message->getPropertyPath() . ': ' . $message->getMessage();
        }

        if(count($this->authorRepository->getUniques($author->getName(), $author->getSurname(),
            $author->getPatronymic()))){
            $errors[] = 'not unique author';
        }
        return $errors;
    }
}