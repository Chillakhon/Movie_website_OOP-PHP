<?php

namespace App\kernel\Upload;

interface UploadedFileInterface
{
    public function move(string $path,$fileName = null): string|false;

    public function getExtension(): string;

    public function hasError(): bool;
}