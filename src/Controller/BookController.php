<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\FileRepository;
use App\Service\BookService;
use App\Service\FileService;
use App\Validator\BookValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private $bookRepository;
    private $bookValidator;
    private FileService $fileService;


    public function __construct(BookRepository $bookRepository, BookValidator $bookValidator, FileService $fileService, FileRepository $fileRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->bookValidator = $bookValidator;
        $this->fileService = $fileService;
    }

    public function index(Request $request): Response
    {
        $book = $this->bookRepository->findOneBy(['id' => $request->get('id')]);
        return $this->render('book/index.html.twig', [
            'book' => $book,
        ]);
    }

    public function list(): Response
    {
        $books = $this->bookRepository->findAll();
        return $this->render('book/list.html.twig', [
            'books' => $books,
        ]);
    }

    public function newBook(): Response
    {
        $book = new Book();

        return $this->render('book/index.html.twig', [
            'book' => $book
        ]);
    }

    public function create(Request $request): Response
    {
        $book = new Book();
        if ($request->files->get('image')) {
            $file = $request->files->get('image');
            $this->fileService->save($file);
            $book->setImage($file);
        }

        BookService::setBook($request, $book);
        $errors = $this->bookValidator->validate($book);
        if (count($errors)) {
            return $this->render('book/index.html.twig', [
                'book' => $book,
                'errors' => $errors
            ]);
        } else {

            $this->bookRepository->save($book);

            return $this->redirectToRoute('book_list');
        }

    }

    public function update(Request $request): Response
    {
        $book = $this->bookRepository->findOneBy(['id' => $request->get('id')]);


        if ($request->files->get('image')) {
            $file = $this->fileService->save($request->files->get('image'));
            $oldImage = $book->getImage();
            $book->setImage($file);
        }

        BookService::setBook($request, $book);
        $errors = $this->bookValidator->validate($book);


        if (count($errors)) {
            return $this->render('book/index.html.twig', [
                'book' => $book,
                'errors' => $errors
            ]);
        } else {
            if ($request->files->get('image')) {

                $this->fileService->delete($oldImage);
            }
            $this->bookRepository->save($book);
            $book = $this->bookRepository->findOneBy(['id' => $request->get('id')]);
            return $this->render('book/index.html.twig', [
                'book' => $book,
                'msg' => 'сохранено'
            ]);
        }


    }

    public function remove(Request $request): Response
    {
        $book = $this->bookRepository->findOneBy(['id' => $request->get('id')]);

        $this->bookRepository->remove($book);
        return $this->redirectToRoute('book_list');
    }

    public function searchByName(Request $request): JsonResponse
    {
        return $this->json($this->bookRepository->searchByName($request->get
        ('name')));
    }

}
