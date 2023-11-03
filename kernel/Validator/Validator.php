<?php

namespace App\kernel\Validator;

class Validator implements ValidatorInterface
{
    private $passed = false, $erorrs = [];

    public function check($source,$items = [])
    {
        foreach ($items as $item =>$rules)
        {
            foreach ($rules as $rule=>$rule_value)
            {
                $value = $source[$item];
                if ($rule == 'required' && empty($value))
                {
                    $this->addError( "{$item} is required",$item);
                }else if (!empty($value))
                {
                    switch ($rule)
                    {
                        case 'min':
                            if (strlen($value) < $rule_value)
                            {
                                $this->addError("$item must be a minimum of $rule_value characters.",$item);
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value)
                            {
                                $this->addError("$item must be a maximum $rule_value characters.",$item);
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value])
                            {
                                $this->addError("$rule_value must match $item",$item);
                            }
                            break;
                        case 'unique':
                            $check = $this->db->get($rule_value,[$item, '=', $value]);
                            if ($check->count())
                            {
                                $this->addError("$item already exists",$item);
                            }
                            break;
                        case 'email':
                            if (!filter_var($value,FILTER_VALIDATE_EMAIL))
                            {
                                $this->addError("$item не правильный формат",$item);
                            }
                            break;
                    }
                }
            }
        }
        if (empty($this->erorrs))
        {
            $this->passed = true;
        }
        return $this;
    }

    public function addError($error, $item = null)
    {
        $this->erorrs[] = [$item => $error];
    }

    public function errors()
    {
        return $this->erorrs;
    }

    public function passed()
    {
        return $this->passed;
    }



}
