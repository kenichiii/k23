<?php

namespace App\Model\WebApp;

use k23\ORM\DbModel;
use k23\ORM\DataType\PrimaryKey;
use k23\ORM\DataType\Bit;
use k23\ORM\DataType\Int;
use k23\ORM\DataType\Varchar;
use k23\ORM\DataType\LangVarchar;
use k23\ORM\DataType\LangText;
use k23\ORM\DataType\Datetime;
use App\Model\WebApp\DataType\AppPageAccess;

class AppPageModel extends DbModel
{
    protected function init()
    {
        $this
           ->model_add('id', new PrimaryKey())     
           ->model_add('uri', new Varchar(255))     
           ->model_add('lang', new  Varchar(10,['unique'=>['uri']])) 
           ->model_add('name', new Varchar(255,['key'=>true]))               
           ->model_add('parentId', new Int(11,['key'=>true]))          
           ->model_add('layout', new Varchar(255,['default'=>'MyDefault']))        
           ->model_add('access', new AppPageAccess(['default'=>'']))     
           ->model_add('title', new LangVarchar(255))           
           ->model_add('menuname', new LangVarchar(255))     
           ->model_add('description', new LangText())          
           ->model_add('content', new LangText())                
           ->model_add('active', new Bit(['default'=>0,'key'=>true]))     
           ->model_add('deleted', new Bit(['default'=>0,'key'=>true]))
           ->model_add('lastUpdate', new Datetime())
           ->model_add('created', new Datetime())     
         ;        
    }
}
