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
        $file_name = $_FILES['image']['name'];
        $file_size =$_FILES['image']['size'];
        $file_tmp =$_FILES['image']['tmp_name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

        $extensions= array("jpeg","jpg","png");

        if(in_array($file_ext, $extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }

        if($file_size < 2097152){
            move_uploaded_file($file_tmp,$location . $file_name);
            return $file_name;
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