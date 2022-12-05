<?php

namespace App\Validator;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookValidator extends BaseValidator
{
    private BookRepository $bookRepository;

    public function __construct(ValidatorInterface $validator, BookRepository $bookRepository)
    {
        parent::__construct($validator);
        $this->bookRepository = $bookRepository;
    }

    public function validate(Book|\App\Entity\EntityInterface $entity): array
    {
        $errors = parent::validate($entity);
        if (count($this->bookRepository->getUniques($entity->getId(), $entity->getName(),
            $entity->getYear(),
            $entity->getISBN()))) {
            $errors[] = 'not unique book';
        }
        return $errors;
    }

}