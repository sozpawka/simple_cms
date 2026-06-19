<?php

trait ImageUploader
{
    public function uploadImage($file)
    {
        if (empty($file['name'])) {
            return null;
        }

        $fileName = time() . '_' . basename($file['name']);

        $uploadPath = 'uploads/' . $fileName;

        move_uploaded_file(
            $file['tmp_name'],
            $uploadPath
        );

        return $fileName;
    }

    public function deleteImage($fileName)
    {
        $path = 'uploads/' . $fileName;

        if (file_exists($path)) {
            unlink($path);
        }
    }
}