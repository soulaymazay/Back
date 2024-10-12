<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file,string $targetDirectory,int $id): string
    {

        $fileName = $id.'.'.$file->guessExtension();
        $file->move($targetDirectory, $fileName);
        return $fileName;
    }

}
?>