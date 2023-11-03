<?php

namespace App\kernel\Validator;

interface ValidatorInterface
{
    public function check($source,$items = []);

    public function addError($error);

    public function errors();

    public function passed();

}