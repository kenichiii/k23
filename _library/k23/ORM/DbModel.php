<?php

namespace k23\ORM;

class DbModel extends PropertyGroup
{
    protected $gridClass = 'DbQuery';    
    
    protected $relations = [];
    
    protected $isRelation_Join = false;    
    protected $isRelation_11 = false;
    protected $isRelation_N1 = false;    
    protected $isRelation_NN = false;
    
    protected $relation_From_column;
    protected $relation_To_column;
        
    protected $grid = null;    
    
    protected $relation_parent = null;    
  
    public static function loadByPK($id)
    {
        $acc = get_called_class();
        $ins = new $acc();
        
        return $ins->getGrid()->getByPK($id);
    }
  
  
    public function setRelations($rels)
    {
        $this->relations = $rels;
    }

    public function setParentRelation($m)
    {
        $this->relation_parent = $m;
    }

    public function getParentRelation()
    {
        return $this->relation_parent;
    }    
    
    public function isNotParentRelation($class)
    {
        if(!$this->getParentRelation()) {
            return true;
        } elseif(is_string($class)) {            
            return $class != get_class($this->getParentRelation());
        }
        else {
            return get_class($class) != get_class($this->getParentRelation());
        }
    }
    
    public function getModelName()
    {
        return $this->name ?: $this->rawname;
    }    

    public function modeladd($name, $model)
    {
        $this->model [$name]= $model;
        $this->model [$name]->setName($name);
                       
        return $this;
    }  
    
    public function getRelations()
    {
        return $this->relations;
    }
    
    
    public function has($name)
    {
        $expl = explode('_',$name);
        
        if(count($expl)==1){
            return isset($this->relations[$name]) || isset($this->model[$name]);
        }
        else {
          if($this->has($expl[0])) {
            $coll = $this->get($expl[0]);
            
            $pieces = [];
            for ($i=1;$i<count($expl);$i++)
            {
                $pieces []= $expl[$i]; 
            }
            
            if($coll->isGroup()) {
                return $coll->has(implode ('_', $pieces));
            } elseif($coll->isModel()) {
                return $coll->has(implode ('_', $pieces));
            } else {
                return false;
            }
          } else {
              return false;
          }
        }
    }
    
    public function modelHas($name)
    {
        $expl = explode('_',$name);
        
        if(count($expl)==1) {
            return isset($this->model[$name]);
        } else {
          if($this->has($expl[0])) {
            $coll = $this->get($expl[0]);
            
            $pieces = [];
            for ($i=1;$i<count($expl);$i++)
            {
                $pieces []= $expl[$i]; 
            }
            
            if($coll->isGroup()) {
                return $coll->has(implode ('_', $pieces));
            } else {
                return false;
            }
          } else {
              return false;
          }
        }
    }    
    
    protected function getParentModelPrefix($name,$prefix='')
    {
        $expl = explode('_',$name);
        
        if(count($expl)==1) {
            if(isset($this->model[$name])) {
                return $prefix;
            } elseif(isset($this->relations[$name])) {
                return $this->relations[$name]->getModelName().'_';
            } else {
                throw new \Exception($name.' cant getParentModelPrefix');
            }
        } else {
            if($this->has($expl[0])) {
                $coll = $this->get($expl[0]);

                $pieces = [];
                for ($i=1;$i<count($expl);$i++)
                {
                    $pieces []= $expl[$i]; 
                }

                if($coll->isGroup()) {
                    return $prefix;
                } elseif($coll->isModel()) {
                    return $coll->getParentModelPrefix(implode ('_', $pieces),$prefix.$coll->getModelName().'_');
                } else {
                    throw new \Exception($name.' cant getParentModelPrefix');
                }
            } else {
                throw new \Exception($name.' cant getParentModelPrefix');
            }
        }        
    }
    
    protected function getParentModel($name)
    {
        $expl = explode('_',$name);
        
        if(count($expl)==1) {
            if(isset($this->model[$name])) {
                return $this;
            } elseif(isset($this->relations[$name])) {
                return $this->get($name);
            } else {
                throw new \Exception($name.' cant getParentModel');
            }
        } else {
            if($this->has($expl[0])) {
                $coll = $this->get($expl[0]);

                $pieces = [];
                for ($i=1;$i<count($expl);$i++)
                {
                    $pieces []= $expl[$i]; 
                }
                
                if($coll->isGroup()) {
                    return $this;
                } elseif($coll->isModel()) {
                    return $coll->getParentModel(implode ('_', $pieces));
                } else {
                    throw new \Exception($name.' cant getParentModel');
                }
            }
            else {
                throw new \Exception($name.' cant getParentModel');
            }
        }
    }        
    
    public function modeladdPkId()
    {        
        $this->modeladd('id', new PkId());
        return $this;
    }

    public function modeladdParentId()
    {
        $this->modeladd('parentid', new ParentId());        
        return $this;
    }

    public function modeladdOwnerId()
    {
        $this->modeladd('ownerid', new OwnerId());        
        return $this;
    }    
    
    public function modeladdPointer()
    {
        $this->modeladd('pointer', new Pointer());        
        return $this;
    }     
        
    public function modeladdDeleted()
    {                  
        $this->modeladd('deleted', new Deleted());                
        return $this;
    }    

    public function modeladdActive()
    {                   
        $this->modeladd('active', new Active());                        
        return $this;
    }

    public function modeladdEmail()
    {                   
        $this->modeladd('email', new Email());                        
        return $this;
    }
    
    public function modeladdPrice()
    {                   
        $this->modeladd('price', new Price());                        
        return $this;
    }
    
    public function modeladdPriceBefore()
    {                   
        $this->modeladd('pricebefore', new PriceBefore());                        
        return $this;
    }    
    
    public function modeladdLang()
    {
        $this->modeladd('lang', new Lang());                                
        return $this;
    }        

    public function modeladdUserId()
    {
        $this->modeladd('userid', new UserId());                        
        return $this;
    }    

    public function relationaddJoinUser()
    {
        $userid = $this->isUserIdAble();
        $usergrid = new Project::$defaultUserGrid();
        $user = $usergrid->getModel();
        $user->setJoin('id',$userid->getCollum());
        $this->relationadd('user',$user);                        
        return $this;
    }    
    
    public function relationaddJoinParent()
    {
        $pid = $this->isParentIdAble();
        $pclass = $this->getGrid()->getModelClassname();
        $p = new $pclass();
        $p->getGrid()->setAlias('parent');
        $p->setJoin('id',$pid->getCollum());
        $this->relationadd('parent',$p);                        
        return $this;
    }        
    
    public function modeladdFile()
    {
        $this->modeladd('file', new File());                                
        $this->get('file')->setDir('docs/'.$this->getRawName().'/file');
        return $this;
    }        
      
    public function modeladdTitle($type=null)
    {     
        $this->modeladd('title', new Title($type));                                         
        return $this;
    }    
    
    public function modeladdPerex()
    {        
        $this->modeladd('perex', new Perex());
        return $this;
    }    

    public function modeladdContent()
    {        
        $this->modeladd('content', new Content());
        return $this;
    }    
    
    public function modeladdPhoto()
    {
        $this->modeladd('photo', new Photo());                                        
        $this->get('photo')->setDir('docs/'.$this->getRawName().'/photo');                           
        return $this;
    }        
    
    public function modeladdPrivatePhoto()
    {
        $this->modeladd('privatephoto', new PrivatePhoto());                                        
        $this->get('privatephoto')->setDir('docs/'.$this->getRawName().'/privatephoto');                           
        return $this;        
    }

    public function modeladdUri()
    {
        $this->modeladd('uri', new Uri());                                        
        return $this;
    }       
    
   public function modeladdNestedIndexes()
   {
       $this->modeladd('nix', new Nix());                                        
        return $this;        
   }
    
    public function modeladdRank($sorting)
    {
        $this->modeladd('rank', new Rank($sorting));                                                                     ; 
        return $this;
    }          
    
    public function modeladdCreated()
    {
        $this->modeladd('created', new Created());
        return $this;
    }         

    public function modeladdLastUpdate()
    {
        $this->modeladd('lastUpdate', new LastUpdate());
        return $this;
    }     

    public function modeladdDate($modeltype = null)
    {
        $this->modeladd('date', new Date($modeltype));        
        return $this;
    }    

    public function relationsadd($name,DbModel $model)
    {        
        $this->relations[$name]= $model;
        $this->relations[$name]->setName($name);        
        return $this;
    }             

    public function removemodel($name)
    {
        unset($this->model[$name]);                            
        return $this;
    }             

    public function removerelation($name)
    {
        unset($this->relations[$name]);                            
        return $this;
    }                 

    public function __call($name, $arguments) 
    {
        $child = parent::__call($name, $arguments);
        
        if($child !== null) {
            return $child;
        } elseif(preg_match('/^(get)/', $name)) {        
            $test = strtolower(preg_replace('/^(get)/', '', $name));    

            $relsall = $this->getRelations();
            $relskeys = array_keys($relsall);

            if(in_array($test,$relskeys)) {            
                if($relsall[$test]->isN1()) {
                    return $this->getGridRelN1($relsall[$test], $arguments);
                } elseif($relsall[$test]->isJoin()) {
                    return $this->relations[$test];
                } elseif($relsall[$test]->isNN()) {
                    return $this->getGridRelNN($relsall[$test], $arguments);
                } elseif($relsall[$test]->is11()) {
                    return $this->getGridRel11($relsall[$test], $arguments);
                } else {
                    throw new \Exception ("not defined relationship ".get_class($this)." :: {$test} ");
                }
            } else {
                throw new \Exception ("not existing relationship ".get_class($this)." :: {$test} ");
            }
        }
        elseif(preg_match('/^(is)/',$name)&&preg_match('/(Able)$/',$name)) {
            $defClass = '\\k23\ORM\\DataType\\Primary\\'.preg_replace('/(Able)$/','',substr($name,2));
            if(class_exists($defClass)) {
                return $this->isColumnAble($defClass);
            } else {
                new \Exception (get_class($this)." :: isCollumAble not exisitng PFC Model Primary {$defClass}");        
            }
        }
        elseif(preg_match('/^(modeladd)/',$name)) {
            $defClassName = preg_replace('/^(modeladd)/','',$name);
            $defClass = '\\k23\\ORM\\DataType\\Primary\\'.$defClassName;
            if(class_exists($defClass)) {
                return $this->modeladdColumn(strtolower ($defClassName), $defClass);
            } else {
                throw new \Exception ("{$this->getGrid()->getModelClassName()} :: not exisitng PFC Model Primary {$defClass}");        
            }
        } else {
            throw new \Exception (get_class($this)." :: not have {$name}");
        }
    }

    protected function modeladdColumn($name,$class)
    {
        $this->modeladd($name, new $class());
        return $this;
    }    
    
    public function isColumnAble($class)
    {
        $able = false;
        foreach($this->getModel() as $key=>$ch) {    
            if( ( $ch instanceof $class )
             || ( $ch instanceof Trans && $ch->getCollumModel() instanceof $class )
             //|| ( $ch instanceof Hybrid && $ch->getHybridModel() instanceof $class )       
             )
            {
                return $ch;
            } 
        }
        
        return $able;        
    }
    
    public function getFromColumnName()
    {
        return $this->relation_From_column;
    }
    
    public function getToColumnName()
    {
        return $this->relation_To_column;
    }
    
    public function getGridRelN1($fromModel, $arguments)
    {
       return $fromModel->getGrid(true)->andWhere( 
               $fromModel->getGrid()->getAlias($fromModel->getFromColumnName()) 
               .'='.$fromModel->get($fromModel->getFromColumnName())->getDibiMod(), 
               $this->get($fromModel->getToColumnName())->getValue() 
          );
    }
    
    public function getGridRelNN($nnModel, $arguments)
    {
        
      $fromModel = $nnModel->getFromModel();  
      $toModel = $nnModel->getToModel();  
      
      if($this->getRawName()==$fromModel->getRawName()) {
          $toGrid = $toModel->getGrid();
          $query = $toGrid->getAlias($nnModel->getToModelCollumName())." in "
                  . "("      
                  .   "SELECT [{$nnModel->getToCollumName()}] FROM [{$nnModel->getGrid()->getTableRaw()}] "
                  .   " WHERE [{$nnModel->getFromCollumName()}]=".$nnModel->get($nnModel->getFromCollumName())->getDibiMod()
                  . ")";
           
          $toGrid->andWhere( $query, $this->get($nnModel->getFromModelCollumName())->getValue());
        
        return $toGrid;        
      }
      else {          
          $fromGrid = $fromModel->getGrid();
          $query = $fromGrid->getAlias($nnModel->getFromModelCollumName())." in "
                  . "("      
                  .   "SELECT [{$nnModel->getFromCollumName()}] FROM [{$nnModel->getGrid()->getTableRaw()}] "
                  .   " WHERE [{$nnModel->getToCollumName()}]=".$nnModel->get($nnModel->getToCollumName())->getDibiMod()
                  . ")";
          
          $fromGrid->andWhere( $query, $this->get($nnModel->getToModelCollumName())->getValue());
          
          return $fromGrid;                  
      }
    }   
    
    public function getModelRel11($model11, $arguments)
    {
     $fromModel = $model11->getFromModel();  
      if($this->getRawName()==$fromModel->getRawName())  
      {
          $toModel = $model11->getToModel();  
          $toGrid = $toModel->getGrid();
          $toGrid->andWhere( $toGrid->getAlias($toModel->getPrimaryKey()->getCollum())." in {"
                  . "SELECT [{$model11->getToCollumName()}] FROM [{$model11->getGrid()->getTableRaw()}] "
                  . " WHERE [{{$model11->getFromCollumName()}}]=".$fromModel->get($model11->getFromCollumName())->getDibiMod(),
                       $fromModel->get($model11->getFromCollumName())->getValue()
             );
          return $toGrid->getSingle();        
      }
      else {
          $toModel = $model11->getToModel();  
          $fromGrid = $fromModel->getGrid();
          $fromGrid->andWhere( $fromGrid->getAlias($fromModel->getPrimaryKey()->getCollum())." in {"
                  . "SELECT [{$model11->getFromCollumName()}] FROM [{$model11->getGrid()->getTableRaw()}] "
                  . " WHERE [{{$model11->getToCollumName()}}]=".$toModel->get($model11->getToCollumName())->getDibiMod(),
                       $fromModel->get($model11->getToCollumName())->getValue()
             );
          return $fromGrid->getSingle();                  
      }
    }

    
    
    public function setGrid($grid,$local=false)
    {
       $this->grid = $grid;        
       if(!$local) $this->_gridClass =  get_class ($grid);           
       return $this;        
    }    
    
    
    public function get($child)
    {
        
            $test = explode('_',$child);
        
            $rek = array();
            for($i=1; $i < count($test); $i++)
            {
                $rek []= $test[$i];
            }
            

            
            if(isset($this->_model[$test[0]]) && count($rek) )
                return $this->_model[$test[0]]->get(implode('_',$rek));
            
            elseif(isset($this->_model[$test[0]]) && !count($rek) )     
                return $this->_model[$test[0]]; 
            
            if(isset($this->_rels[$test[0]]) && count($rek) )
                return $this->_rels[$test[0]]->get(implode('_',$rek));
            
            elseif(isset($this->_rels[$test[0]]) && !count($rek) )     
                return $this->_rels[$test[0]]; 

            
            else throw new \Exception (get_class($this)." cant get {$child}");        
    }
    
    
    public function hasCollumName($child_collum_name)
    {
        foreach($this->getCollumsRaw() as $key=>$c)
        {
            if($c->getCollumName()==$child_collum_name)
                return true;
        }
            
        if($this->getPrimaryKey()->getCollumName()==$child_collum_name)
                return true;
        
        return false;
    }
    
    public function getCollumName($child_collum_name)
    {
        foreach($this->getCollumsRaw() as $key=>$c)
        {
            if($c->getCollumName()==$child_collum_name)
                return $this->get($c->getCollum());
        }
            
        if($this->getPrimaryKey()->getCollumName()==$child_collum_name)
                return $this->get($this->getPrimaryKey()->getCollum());
        
        throw new \Exception (get_class($this)." cant getCollum {$child_collum_name}");        
    }
    
    public function set($child, $value, $from = null) {
            $test = explode('_',$child);
        
            $rek = array();
            for($i=1; $i < count($test); $i++)
            {
                $rek []= $test[$i];
            }
            
            if(isset($this->_model[$test[0]]) && count($rek) && ! $this->_model[$test[0]]->isPrimitive() )
                $this->_model[$test[0]]->set(implode('_',$rek),$value,$from);
            
            elseif(isset($this->_model[$test[0]]) && !count($rek) )     
                $this->_model[$test[0]]->set($value,$from);
            
            elseif(isset($this->_rels[$test[0]]) && count($rek) )
                $this->_rels[$test[0]]->set(implode('_',$rek),$value,$from);
            
            elseif(isset($this->_rels[$test[0]]) && !count($rek) )     
                $this->_rels[$test[0]]->set($value,$from);
            
            else throw new \Exception (get_class($this)." cant set {$child}");
            
            return $this;        
    }
    
    
    
    /**
     * 
     * @param boolean $fresh
     * @return Model_Grid
     */
    public function getGrid($fresh=false,$isTest=false)
    {
       if($this->grid===null||$fresh)
       {
           $this->grid = new $this->_gridClass();
       }
       
       if($isTest) $this->grid->setIsTest(true);
       
       return $this->grid;        
    }
    
    public function getGridClassName()
    {
        return $this->_gridClass;
    }
    
    public function setN1($fromthis,$toparent)
    {
        $this->_relN1 = true;
        $this->_relFrom = $fromthis;
        $this->_relTo = $toparent;
        
        return $this;
    }
    public function isN1() { return $this->_relN1; }
    
    public function is11() { return $this->_rel11; }
    

    public function isNN() { return $this->_relNN; }
    
    public function isJoin()
    {
        return $this->_isJoin;
    }
    
    public function setJoin($fromthis,$toparent)
    {
            $this->_isJoin = true;
        
            $this->_relFrom = $fromthis;
            $this->_relTo = $toparent;
        
        return $this;     
    }
    
    
    
    public function fromDb($data2) {
        foreach($data2 as $key2=>$value2)
        {       
           if($this->has($key2))
                $this->set( $key2, $value2, 'db' );     
        }
                
        return $this;
    }
    
    public function fromform($data) {
                
          parent::fromform($data);               
        
          foreach ($this->getModel() as $key => $child) 
          { 
                if( $child->isModel() )
                {
                     $this->get($child->getModelName())->fromform($data);                                
                }
          } 
           
          //add relations  
                
        return $this;
    }
    

    public function isModel()
    {
        return true;
    }
    
    public function isGroup()
    {
        return false;
    }    
    
    
    public function validate($formAction=null,$data=null,$model=null)
    {
        if($model==null) $model = $this;
        
        $val = new Validation();
        
        $val->add(parent::validate($formAction,$data,$model));
        
        $val->add($this->checkUniques($formAction,$data));
        
        return $val;
    }
    
    public function checkUniques($action,$data=null)
    {
        $val = new Validation();
        
        if($action==self::FORM_ACTION_NEW) {
            foreach($this->getCollumsRaw() as $key=>$collum)
            {
                if($collum->isUnique() && $collum->getUniqueWith()!=null && is_array($collum->getUniqueWith()) )
                {
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                    foreach( $collum->getUniqueWith() as $key2 => $c )
                        $grid->where( " and ".$grid->getAlias($this->{"get{$c}"}()->getCollumName()).'='.$this->{"get{$c}"}()->getDibiMod(),$this->{"get{$c}"}()->getValue());
                        
                    if( $grid->getCount() > 0 )    
                    $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Skupina není jedinečná','section-models'));
                }
                elseif($collum->isUnique() && $collum->getUniqueWith()!=null && is_string($collum->getUniqueWith()) )
                {
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                    $grid->where( " and ".$grid->getAlias($this->{"get{$collum->getUniqueWith()}"}()->getCollumName()).'='.$this->{"get{$collum->getUniqueWith()}"}()->getDibiMod(),$this->{"get{$collum->getUniqueWith()}"}()->getValue());
                        
                    if( $grid->getCount() > 0 )    
                    $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Skupina není jedinečná','section-models'));
                    //$val->addError ('notunique', $collum->getCollum(), 'Dvojice  '.$collum->getTitle().' a '.$this->{"get{$collum->getUniqueWith()}"}()->getTitle().' není jedinečná');
                }
                elseif($collum->isUnique() && $collum->getUniqueWith()==null )
                {
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                        
                    if( $grid->getCount() > 0 )    
                    $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Položka [#item-title#] není jedinečná','section-models',array('item-title'=>$collum->getTitle())));
                }
            }
        }
        elseif($action==self::FORM_ACTION_EDIT) {
            
            $old = $this->getGrid()->getByPk($this->getPrimaryKey()->getValue());

            foreach($this->getCollumsRaw() as $key=>$collum)
            {
                if($collum->isUnique() && $collum->getUniqueWith()!=null && is_array($collum->getUniqueWith()) )
                {
                  $same = true;  
                  if( $old->{"get{$collum->getCollum()}"}()->getValue() == $collum->getValue() )  
                  {
                    foreach( $collum->getUniqueWith() as $key2 => $c )
                        if( $old->{"get{$c}"}()->getValue() == $this->{"get{$c}"}()->getValue() )  {}
                        else {
                            $same = false;
                        }
                  } else $same = false;
                  
                  if(!$same)
                  {
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                    foreach( $collum->getUniqueWith() as $key2 => $c )
                        $grid->where( " and ".$grid->getAlias($this->{"get{$c}"}()->getCollumName()).'='.$this->{"get{$c}"}()->getDibiMod(),$this->{"get{$c}"}()->getValue());                    
                    
                     if( $grid->getCount() > 0 )    
                     $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Skupina není jedinečná','section-models'));
                  }
                }
                elseif($collum->isUnique() && $collum->getUniqueWith()!=null && is_string($collum->getUniqueWith()) )
                {
                   
                  if( $old->{"get{$collum->getCollum()}"}()->getValue() != $collum->getValue() || $old->{"get{$collum->getCollum()}"}()->getValue() != $this->{"get{$collum->getUniqueWith()}"}()->getValue() )  
                  { 
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                    $grid->where( " and ".$grid->getAlias($this->{"get{$collum->getUniqueWith()}"}()->getCollumName()).'='.$this->{"get{$collum->getUniqueWith()}"}()->getDibiMod(),$this->{"get{$collum->getUniqueWith()}"}()->getValue());
                        
                    if( $grid->getCount() > 0 )    
                        $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Skupina není jedinečná','section-models'));
                    //$val->addError ('notunique', $collum->getCollum(), 'Dvojice  '.$collum->getTitle().' a '.$this->{"get{$collum->getUniqueWith()}"}()->getTitle().' není jedinečná');
                  }
                }
                elseif($collum->isUnique() && $collum->getUniqueWith()==null )
                {
                  if( $old->{"get{$collum->getCollum()}"}()->getValue() != $collum->getValue() )  
                  { 
                    $grid = $this->getGrid()->clear();
                    $grid->where( " and ".$grid->getAlias($collum->getCollumName()).'='.$collum->getDibiMod(),$collum->getValue());
                        
                    if( $grid->getCount() > 0 )    
                    $val->addError ('notunique', $collum->getCollum(), PFC\WebApp\_t('Položka [#item-title#] není jedinečná','section-models',array('item-title'=>$collum->getTitle())));
                  }
                }
            }
        }
        
        return $val;
    }
    
    /**
     * 
     * @return int $newId
     */
    public function insert($isTest=false,$freshGrid=false)
    {
        if($isTest)
            return $this->getGrid($freshGrid,true)->insert( $this->getCollumsNamesInArray() );
        else {
            $id = $this->getGrid($freshGrid,false)->insert( $this->getCollumsNamesInArray() );

            $this->set($this->getPrimaryKey()->getCollum(),$id);
            return $id;
        }
    }
    
    
    /**
     *  update database record by primary key for all model defined collums
     * 
     *  @return Model_Model $this
     */
    public function update($isTest=false,$freshGrid=false)
    {
        if($isTest)
            return $this->getGrid($freshGrid,true)->updateByPK( $this->getCollumsNamesForUpdate(), $this->getPrimaryKey()->getValue() );
        else {
            $this->getGrid($freshGrid,false)->updateByPK( $this->getCollumsNamesForUpdate(), $this->getPrimaryKey()->getValue() );
        
            return $this;
        }
    }
    
    
    public function delete($isTest=false,$freshGrid=false)
    {
        
        if($isTest)
            return $this->getGrid($freshGrid,$isTest)->deleteByPK( $this->getPrimaryKey()->getValue() );
        else
        {
            $this->getGrid($freshGrid,$isTest)->deleteByPK( $this->getPrimaryKey()->getValue() );
        
            return $this;
        }
    }

    
    
    
    
    public function isNestedAble()
    {
        $able = false;
        foreach($this->getModel() as $key=>$ch) {    
            if($ch->isDefault()&&$ch instanceof Model_Default_Nix)
            {
                return $ch;
            } 
        }
        
        return $able;
    }
    
    
    
    public function isGalleryAble()
    {
        $able = false;
        foreach($this->getRels() as $key=>$ch) {    
            if( $ch instanceof Component\Gallery\Model )
            {
                return $ch;
            } 
        }
        
        return $able;
    }       

    public function isDocsAble()
    {
        $able = false;
        foreach($this->getRels() as $key=>$ch) {    
            if( $ch instanceof Component\FilesGrid\Model && !$ch instanceof Component\Gallery\Model)
            {
                return $ch;
            } 
        }
        
        return $able;
    }       
    
    
    /**
     * @return Model_Primitive database primary key model
     */
    public function getPrimaryKey()
    {
        foreach ($this->getModel() as $key=>$child)
            if($child->isPrimitive()&&$child->isPrimaryKey())
                return $child;
        return null;    
    }
    
    public function fromJson($json)
    {
        $o = json_decode($json);
        foreach($o as $c=>$v)
        {
            if($this->has($c))
                $this->set($c,$v);
        }
        
        return $this;
    }

    public function toJson()
    {
        return json_encode($this->getCollumsInArray());
        foreach($o as $c=>$v)
        {
            if($this->has($c))
                $this->set($c,$v);
        }
        
        return $this;
    }    
}

