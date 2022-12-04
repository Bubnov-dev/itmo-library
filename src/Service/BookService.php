<?php

namespace App\Service;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

class BookService{


    /**
     * @param Request $request
     * @param Book $book
     * @return void
     */
    public static function setBook(Request $request, Book $book): void
    {
        $request->get('name') ? $book->setName($request->get('name')) : '';
        $request->get('year') ? $book->setYear($request->get('year')) : '';
        $request->get('isbn') ? $book->setISBN($request->get('isbn')) : '';
        $request->get('pageNumber') ? $book->setPageNumber($request->get('pageNumber'))
            : '';
    }
}