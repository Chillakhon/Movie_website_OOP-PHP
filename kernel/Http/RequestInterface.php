<?php

namespace App\kernel\Http;

use App\kernel\Upload\UploadedFileInterface;
use App\kernel\Validator\Validator;

interface RequestInterface
{
    public static function createFromGlobals(): static;

    public function uri();

    public function method();

    public function input(string $key, $default = null): mixed;

    public function file(string $key): ?UploadedFileInterface;

    public function validate(array $items);

    public function errors();

    public function setValidator(Validator $validator): void;







}
