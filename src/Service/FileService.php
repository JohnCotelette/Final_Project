<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileService
 * @package AppBundle
 */
class FileService
{

    /**
     * @param string $fileSrc
     * @param string $fileDest
     * @return void
     */
    public function copyFile(string $fileSrc, string $fileDest) :void
    {
        if (!file_exists($fileDest)) {
            copy($fileSrc, $fileDest);
        }
    }

    /**
     * @param string $dest
     * @param string $copyName
     * @return UploadedFile
     */
    public function createUploadFile(string $dest, string $copyName) :UploadedFile
    {
        return new UploadedFile(
            $dest,
            $copyName,
            "image/png",
            filesize($dest),
            null,
            true
        );
    }

    function savePicture(UploadedFile $image, $dir)
    {
        $path = $this->kernel->getProjectDir() . $dir;
        $imageName = uniqid() . '.' . $image->guessExtension();
        $image->move($path,$imageName);
        return $imageName;
    }
}