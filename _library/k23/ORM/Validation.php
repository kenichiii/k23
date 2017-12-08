<?php

namespace k23\ORM;

class Validation
{
    protected $errors = [];
    
    public function addError($type,$element,$message) {
        $this->errors[]= [
            'element' => $element,
            'message' => $message,
            'type' => $type
        ];
        
        return $this;
    }
    
    public function add(Validation $v)
    {
       $this->errors = array_merge($this->errors,$v->getErrors());
       return $this;
    }
    
    public function getErrors()
    {
        return $this->errors;
        
    }
    
    public function isSucc()
    {
        return count($this->getErrors()) > 0 ? false : true;
    }
}