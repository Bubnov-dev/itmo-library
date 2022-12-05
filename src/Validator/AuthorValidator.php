<?php

namespace App\Validator;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorValidator extends BaseValidator
{
    private AuthorRepository $authorRepository;

    public function __construct(ValidatorInterface $validator, AuthorRepository $authorRepository,)
    {
        parent::__construct($validator);
        $this->authorRepository = $authorRepository;
    }

    public  function validate(Author|\App\Entity\EntityInterface $entity): array
    {
        $errors = parent::validate($entity);

        if(count($this->authorRepository->getUniques($entity->getId(), $entity->getName(),
                                                     $entity->getSurname(),
            $entity->getPatronymic()))){
            $errors[] = 'not unique author';
        }
        return $errors;
    }
}