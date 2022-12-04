<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use App\Validator\AuthorValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authorRepository;
    private $authorValidator;

    public function __construct(AuthorRepository $authorRepository, AuthorValidator $authorValidator)
    {
        $this->authorRepository = $authorRepository;
        $this->authorValidator = $authorValidator;
    }

    public function index(Request $request): Response
    {
        $author = $this->authorRepository->findOneBy(['id' => $request->get('id')]);

        return $this->render('author/index.html.twig', [
            'author' => $author
        ]);
    }

    public function list(){
        $authors = $this->authorRepository->findAll();
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }
    public function newAuthor(): Response
    {
        $author = new Author();

        return $this->render('author/index.html.twig', [
            'author' => $author
        ]);
    }

    public function create(Request $request){
        $author = new Author();
        AuthorService::setAuthor($request, $author);

        $errors = $this->authorValidator->validate($author);
        if (count($errors)){
            return $this->render('author/index.html.twig', [
                'author' => $author,
                'errors' => $errors
            ]);
        }
        else{
            $this->authorRepository->save($author);
            return $this->redirectToRoute('author_list');
        }
    }

    public function update(Request $request){
        $author = $this->authorRepository->findOneBy(['id' => $request->get('id')]);
        AuthorService::setAuthor($request, $author);
        $errors = $this->authorValidator->validate($author);
        if (count($errors)){
            return $this->render('author/index.html.twig', [
                'author' => $author,
                'errors' => $errors
            ]);
        }
        else{
            $this->authorRepository->save($author);
            return $this->redirectToRoute('author_list');
        }
    }

    public function remove(Request $request){
        $author = $this->authorRepository->findOneBy(['id' => $request->get('id')]);

        $this->authorRepository->remove($author);
        return $this->redirectToRoute('author_list');

    }
}
