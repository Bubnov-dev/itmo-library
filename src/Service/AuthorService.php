<?php

namespace App\Service;

use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;

class AuthorService
{
    public static function setAuthor(Request $request, Author $author): void
    {
        $request->get('name') ? $author->setName($request->get('name') ): '';
        $request->get('surname') ? $author->setSurname($request->get('surname')): '';
        $request->get('patronymic') ? $author->setPatronymic($request->get('patronymic'))
            : '';
    }
}