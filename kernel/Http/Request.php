<?php

namespace App\kernel\Http;

use App\kernel\Upload\UploadedFile;
use App\kernel\Upload\UploadedFileInterface;
use App\kernel\Validator\ValidatorInterface;

class Request implements RequestInterface
{
    private ValidatorInterface $validator;

        private array $get;
        private array $post;
        private array $server;
        private array $files;
        private array $cookies;
    public function __construct($get,$post,$server,$files,$cookies)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->files = $files;
        $this->cookies = $cookies;
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_SERVER, $_FILES, $_COOKIE);
    }
    public function uri()
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }
    public function method()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function input(string $key, $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function file(string $key): ?UploadedFileInterface
    {
        if (! isset($this->files[$key])){
            return dd('kdnv');
        }

        return new UploadedFile(
            $this->files[$key]['name'],
            $this->files[$key]['type'],
            $this->files[$key]['tmp_name'],
            $this->files[$key]['error'],
            $this->files[$key]['size'],
        );
    }

    public function validate(array $items)
    {
        $source = [];
     foreach ($items as  $key=>$value){
         $source[$key] = $this->input($key);
     }
          $this->validator->check($source, $items);
           return $this->validator->passed();
    }

    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}