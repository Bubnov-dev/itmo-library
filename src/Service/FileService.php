<?php

namespace App\Service;

use App\Entity\File;
use App\Repository\BookRepository;
use App\Repository\FileRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    private SluggerInterface $slugger;
    private FileRepository $fileRepository;
    private $appKernel;

    public function __construct(SluggerInterface $slugger, FileRepository
    $fileRepository, KernelInterface $appKernel, BookRepository $bookRepository)
    {

        $this->appKernel = $appKernel;
        $this->slugger = $slugger;
        $this->fileRepository = $fileRepository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * saving uploaded file and store it in uploads
     * @param $file
     * @return File
     */
    public function save($file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        $destination = $this->appKernel->getProjectDir() . '\public\uploads';

        $fileEntity = new File();
        $fileEntity->setName($safeFilename);
        $path = $destination . '\\' . $fileName;
        $path = str_replace('\\', '/', $path);
        $fileEntity->setPath($path);

        $this->fileRepository->save($fileEntity);


        try {
            $file->move($destination, $fileName);
        } catch (FileException $e) {
        }

        return $fileEntity;
    }

    /**
     * deleting file Entity and file from, uploads
     * @param $file
     * @return void
     */
    public function delete($file)
    {
        if ($file) {

            $filesystem = new Filesystem();
            $filesystem->remove($file->getPath());
            $book = $this->bookRepository->findOneBy(['image' => $file->getId()]);
            if ($book){
                $book->setImage(null);
                $this->bookRepository->save($book);
            }
            $this->fileRepository->remove($file);
        }


    }

}