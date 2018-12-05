<?php

namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;

class FileService
{
    private $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function uploadImage($location) {

        $errors= array();
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = strtotime(time()) . '_' . $fileName;

        $extensions= array("jpeg","jpg","png");

        if(in_array($fileExt, $extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }

        if($fileSize < 2097152){
            move_uploaded_file($fileTmp,$location . $newFileName);
            return $fileName;
        } else {
            return '';
        }
    }

    function removeFile($directory) {
        if($this->fileSystem->exists($directory)) {
            $this->fileSystem->remove(array($directory));
        }
    }
}