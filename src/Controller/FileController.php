<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use App\Service\FileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileController extends AbstractController
{
    private FileService $fileService;
    private FileRepository $fileRepository;
    public function __construct(FileService $fileService, FileRepository $fileRepository)
    {
        $this->fileService = $fileService;
        $this->fileRepository = $fileRepository;
    }

    public function index(){
        $files = $this->fileRepository->findAll();

        return $this->render('file/index.html.twig', [
            'files' => $files
        ]);
    }

    public function create(Request $request)
    {
        $file = $request->files->get('file');

        $this->fileService->save($file);
        $files = $this->fileRepository->findAll();

        return $this->render('file/index.html.twig', [
            'files' => $files
        ]);
    }

    public function remove(Request $request){
        $file = $this->fileRepository->findOneBy(['id' => $request->get('id')]);
        $this->fileService->delete($file);
        $files = $this->fileRepository->findAll();

        return $this->render('file/index.html.twig', [
            'files' => $files
        ]);

    }


}
