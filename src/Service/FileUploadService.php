<?php

namespace App\Service;


class FileUploadService
{

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
            return $location . $file_name;
        } else {
            return '';
        }
    }
}