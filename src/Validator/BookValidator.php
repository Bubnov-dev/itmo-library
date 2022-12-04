<?php

namespace App\Validator;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookValidator
{
    private $bookRepository, $validator;

    public function __construct(BookRepository $bookRepository, ValidatorInterface $validator)
    {
        $this->bookRepository = $bookRepository;
        $this->validator = $validator;
    }
    public  function validate(Book $book){
        $verrors = $this->validator->validate($book);
        $errors = [];
        foreach ($verrors as $message) {
            $errors[] = $message->getPropertyPath() . ': ' .$message->getMessage();
        }
        if(count($this->bookRepository->getUniques($book->getName(), $book->getYear(),
            $book->getISBN()))){
            $errors[] = 'not unique book';
        }
        return $errors;
    }

}