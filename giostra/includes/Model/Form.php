<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class Form{

  /**
   * @var string $file      
   */

    private $file;

  /**
   * @var Struct $class      
   */

    private $class;

  /**
   * @var Struct $alias      
   */

    private $alias;

  /**
   * @var Struct $conditionselect      
   */

    private $conditionselect;

  /**
   * @var Struct $description      
   */

    private $description;

  /**
   * @var Struct $visibility      
   */

    private $visibility;

  /**
    * @var Struct $custombox      
    */

    private $custombox;


    /**
     * __call()
     *
     * @param $name String
     * @param $argv array
     *
     */

    public function __call($name, $argv){ 
        Log::Error("Fatal error: Call to undefined method ".get_class($this)."::".$name."()");
    }


    /**
     * SetCustomFile()
     *
     * @param $file string
     * @return void
     *
     */ 

    public function SetCustomFile($file){
        $this->file = $file;
    }

    /**
     * SetClass()
     *
     * @param $entity String
     * @param $class String
     * @example $entity="Name_Entity",$class="Name_class"        
     * @return void
     *
     */ 

    public function SetClass($entity, $class){
        $this->class[] = new Struct($entity,$class);
    }


    /**
     * SetConditionSelect()
     *
     * @param $entity String
     * @param $condition Array[][]
     * @example $entity="Name_Entity",$condition=array('name=:n',array(':n'=>'value'))
     * @return void
     *
     */ 

    public function SetConditionSelect($entity, $condition){
        $this->conditionselect[] = new Struct($entity,$condition);
    }


    /**
     * SetAlias()
     *
     * @param $entity String
     * @param $name Array
     * @return void
     *
     */ 

    public function SetAlias($entity, $name){
        $this->alias[] = new Struct($entity,$name);
    }


    /**
     * SetDescription()
     *
     * @param $entity String
     * @param $description String   
     * @return void
     *
     */ 

    public function SetDescription($entity, $description){
        $this->description[] = new Struct($entity,$description);
    }  

    /**
     * SetVisibility()
     *
     * @param $entity String
     * @param $visibility String   
     * @return void
     *
     */ 

    public function SetVisibility($entity, $visibility){
        $this->visibility[] = new Struct($entity,$visibility);
    } 


    /**
     * SetCustomBox()
     *
     * @param $entity String
     * @param $name String   
     * @return void
     *
     */ 

    public function SetCustomBox($entity, $name){
        $this->custombox[] = new Struct($entity,$name);
    } 


    /**
     * getCustomFile()
     *
     * @return string    
     *
     */ 

    public function getCustomFile(){ 
        if (isset($this->file)) 
            return $this->file;
        else return null;        
    }


    /**
     * getClass()
     *
     * @param $name String
     * @return String
     *
     */ 

    public function getClass($name){

        for ($i = 0; $i < length($this->class); $i++){
            if (isset($this->class[$i]))
                if($this->class[$i]->getEntity() == $name)
                    return $this->class[$i]->getValue();
        }

        return null;

    } 


    /**
     * getConditionSelect()
     * 
     * @param $name String 
     * @return Array[]
     *
     */ 

    public function getConditionSelect($name){
        for ($i = 0; $i < length($this->conditionselect); $i++){
            if (isset($this->conditionselect[$i]))
                if($this->conditionselect[$i]->getEntity() == $name)
                    return $this->conditionselect[$i]->getValue();
        }

        return null;

    } 


    /**
     * getAlias()
     * 
     * @param $name String 
     * @return Array[]
     *
     */ 

    public function getAlias($name){
        for ($i = 0; $i < length($this->alias); $i++){
            if (isset($this->alias[$i]))
                if($this->alias[$i]->getEntity() == $name)
                    return $this->alias[$i]->getValue();
        }

        return null;

    } 




    /**
     * getDescription()
     *     
     * @param $name String
     * @return String
     *
     */ 

    public function getDescription($name){
        for ($i = 0; $i < length($this->description); $i++){
            if (isset($this->description[$i]))
                if($this->description[$i]->getEntity() == $name)
                    return $this->description[$i]->getValue();
        }

        return null;

    }  


    /**
     * getVisibility()
     *
     * @param $name String
     * @return bool
     *
     */ 

    public function getVisibility($name){
        for ($i = 0; $i < length($this->visibility); $i++){
            if (isset($this->visibility[$i]))
                if($this->visibility[$i]->getEntity() == $name)
                    return $this->visibility[$i]->getValue();
        }

        return true;

    } 



    /**
     * getCustomBox()
     *
     *@param $name String
     * @return string
     *
     */ 

    public function getCustomBox($name){
        for ($i = 0; $i < length($this->custombox); $i++){
            if (isset($this->custombox[$i]))
                if($this->custombox[$i]->getEntity() == $name)
                    return $this->custombox[$i]->getValue();
        }

        return null;

    } 

               
}