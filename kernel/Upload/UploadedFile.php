<?php

namespace App\kernel\Upload;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        public string $name,
        public string $type,
        public string $tmpName,
        public int $error,
        public int $size,
    ){}

    public function move(string $path, $fileName = null): string|false
    {
        $storagePath = APP_PATH."/storage/$path";

        if (! is_dir($storagePath)){
            mkdir($storagePath,0777,true);
        }

        $fileName = $fileName ?? $this->randomFileName();

        $filePath = "$storagePath/$fileName";

        if (move_uploaded_file($this->tmpName,$filePath)){
            return "$path/$fileName";
        }
        return false;
    }

    private function randomFileName(): string
    {
        return md5(uniqid(rand(),more_entropy: true)).'.'.$this->getExtension();
    }

    public function getExtension(): string
    {
        // вернет расширение файла
        return pathinfo($this->name,PATHINFO_EXTENSION);
    }

    public function hasError(): bool
    {
        return $this->error != UPLOAD_ERR_OK;
    }
}