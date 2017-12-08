<?php

namespace k23\ORM;

class PropertyGroup
{        
    protected $model = [];
    protected $relations = [];
    
    protected $name = null;
    protected $rawname = null;
    
    protected $isInData = true;    
    protected $isInForm = true;
    
    public function getName()
    {
        if(!$this->name) {
            return $this->getRawName();
        }
        
        return $this->name;
    }
        
    public function isModel()
    {
        return true;
    }    
    
    public function isGroup()
    {
        return true;
    }    
    
    public function isBaseProperty()
    {
        return false;
    }

    public function isInnerSql()
    {
        return false;
    }
    
    public function isInForm() {
        return $this->isInForm;
    }
    
    public function setInForm($bool) {
        $this->isInForm = (bool) $bool;
        return $this;
    }      
    
    public function isInData() {
        return $this->isInData;
    }
    
    public function setInData($bool) {
        return $this->isInData = (bool) $bool;
    }  
    
    public function getRawName() {
        if(!$this->rawname) {
            $pieces = explode('\\', get_called_class());
            $this->rawname = end($pieces);
        }
        
        return $this->rawname;
    }

    public function setRawName($name) {
        $this->rawname = $name;
    }
    
    public function has($name)
    {
        $exp = explode('_',$name);
        
        if(count($expl) === 1) {
            return isset($this->model[$name]);
        } else {
          if($this->has($exp[0])) {
            $col = $this->get($exp[0]);            
            $pieces = [];
            for ($i=1;$i<count($expl);$i++)
            $pieces []= $expl[$i];             
            if($col->isGroup()) {
                return $col->has(implode ('_', $pieces));
            } else {
                return false;
            }
          } else {
              return false;
          }
        }                                        
    }
        
    public function modeladd($name, PropertyGroup $model)
    {
        $this->model[$name]= $model;
        $this->model[$name]->setRawName($name);
                            
        return $this;
    }  
    
    public function getModel()
    {
        return $this->model;
    }
    
    public function remove($name=null)
    {
        if($name === null) {
            $this->model = [];
        } elseif(isset($this->model[$name])) {
            unset($this->model[$name]);
        } else {
            throw new \Exception("{$this->getName()} has not have {$name}");
        }
        
        return $this;
    }        
    
    public function setName($name)
    {
        $this->name = $name;        
        
        if($this->isGroup()) {
            $this->buildGroup($name.'_');
        }
        
        return $this;
    }
    
    protected function buildGroup($prefix='')
    {
        foreach($this->getModel() as $key => $child) {
            if($child->isGroup()) {
                $child->buildGroup($prefix.$child->getName().'_');
            } elseif($child->isBaseProperty()) {                
                $child->setName($prefix.$child->getRawName());
            }
        }
    }        
    
    public function set($child, $value, $from = null)
    {
            $test = explode('_',$child);
        
            $rek = [];
            for($i=1; $i < count($test); $i++)
            {
                $rek []= $test[$i];
            }
            
            if($this->has($test[0]) && count($rek) 
                    && !$this->model[$test[0]]->isBaseProperty() 
            ) {
                $this->model[$test[0]]->set(implode('_',$rek), $value, $from);                
            } elseif($this->heas($test[0]) && !count($rek) ) {     
                $this->model[$test[0]]->set($value,$from);            
            } else {
                throw new \Exception ("{$this->getName()} cant set {$child}");
            }
            return $this;
    }
    
    
    
    public function get($child)
    {
            $test = explode('_',$child);
        
            $rek = [];
            for($i=1; $i < count($test); $i++)
            {
                $rek []= $test[$i];
            }

            
            if($this->has($test[0]) && count($rek) > 0 ) {
                return $this->model[$test[0]]->get(implode('_',$rek));
            } elseif($this->has($test[0]) && !count($rek) ) {    
                return $this->model[$test[0]]; 
            } else {
                throw new \Exception ("{$this->getName()} cant get {$child}");        
            }
    }    
    
    public function validate($formAction=null,$data=null,$model=null)
    {
        $val = new Validation();
        foreach ($this->getModel() as $key => $value) {
            if($value->isBaseProperty()) {
                $val->add($this->get($key)->validate($formAction, $data, $model));
            } elseif($value->isGroup()) {                
                foreach($value->getModel() as $mkey => $mvalue) {  
                    if($mvalue->isBaseProperty()) {
                        $val->add(
                            $this->get($mkey)->validate($formAction,$data,$model)
                        );
                    } elseif($mvalue->isGroup()) {
                        $val->add(
                            $this->validateGroup($mvalue,$formAction,$data,$model)
                        );
                    }
              }
                            
            }
        }
        
        return $val;
    }        

    protected function validateGroup($mixed,$formAction,$data,$model)
    {
        $val = new Validation();
        foreach ($mixed->getModel() as $key => $value) {
            if($value->isBaseProperty()) {
                $val->add($this->get($key)->validate($formAction, $data, $model));
            } elseif($value->isGroup()) {
                foreach ($value->getModel() as $mkey => $mvalue) {  
                    if($mvalue->isBaseProperty()) {
                        $val->add(
                            $this->get($mkey)->validate($formAction,$data,$model)
                        );
                    } elseif($mvalue->isGroup()) {
                        $val->add($this->validateGroup(
                                $mvalue,$formAction,$data,$model)
                        );
                    }
                }
            }
        }
        
        return $val;
    }            
                
    public function getCollumsRaw()
    {
        $collums = array();
        
        foreach($this->getModel() as $key=>$child)
        {
            if($child->isInnerSql()||($child->isPrimitive()&&$child->isPrimaryKey()))
                continue;            
                        
            elseif($child->isPrimitive()) 
            {                
                $collums []= $child;
            }
            elseif($child->isMixed())
            {
                $collums = array_merge($collums,$child->getCollumsRaw());
            }
        }
        
        return $collums;
    }
    
    public function getCollumsInArray()
    {
        $collums = array();
        
        foreach($this->getModel() as $key=>$child)
        {
            if($child->isInnerSql()||($child->isPrimitive()&&$child->isPrimaryKey()))
                continue;            
                        
            elseif($child->isPrimitive()) 
            {                
                $collums [$child->getCollum()]= $child->getValue();
            }
            elseif($child->isMixed())
            {                
                $collums = array_merge($collums,$child->getCollumsInArray());
            }
        }
        
        return $collums;
    }

    public function getCollumsNamesInArray()
    {
        $collums = array();
        
        foreach($this->getModel() as $key=>$child)
        {
            if($child->isInnerSql()||($child->isPrimitive()&&$child->isPrimaryKey()))
                continue;            
                        
            elseif($child->isPrimitive()) 
            {                
                $collums [$child->getCollumName()]= $child->getValue();
            }
            elseif($child->isMixed())
            {                
                $collums = array_merge($collums,$child->getCollumsInArray());
            }
        }
        
        return $collums;
    }    
    
    public function getCollumsForUpdate()
    {
        $collums = array();
        
        foreach($this->getModel() as $key=>$child)
        {
            if($child->isInnerSql()||($child->isPrimitive()&&$child->isPrimaryKey()))
                continue;            
                        
            elseif($child->isPrimitive() && $child->isChange()) 
            {                
                $collums [$child->getCollum()]= $child->getValue();
            }
            elseif($child->isMixed())
            {                
                $collums = array_merge($collums,$child->getCollumsForUpdate());
            }
        }
        
        return $collums;
    }
    
    public function getCollumsNamesForUpdate()
    {
        $collums = array();
        
        foreach($this->getModel() as $key=>$child)
        {
            if($child->isInnerSql()||($child->isPrimaryKey()))
                continue;            
                        
            elseif($child->isPrimitive() && $child->isChange()) 
            {                
                $collums [$child->getCollumName()]= $child->getValue();
            }
            elseif($child->isMixed())
            {                
                $collums = array_merge($collums,$child->getCollumsNamesForUpdate());
            }
        }
        
        return $collums;
    }
    
    public function fromform($data)
    {  
        foreach ($this->getCollumsRaw() as $key => $child) 
        {
            
                $this->get($child->getCollum())->fromform($data);                                
           
        }
        
        return $this;
    }
}



