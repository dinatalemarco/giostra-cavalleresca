<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class Struct{



  /**
   * @var string $entity      
   */        
    private $entity;

  /**
   * @var INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE  $value 
   */    
    private $value;


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
     * __construct()
     *
     * @param $entity String
     * @param $value .
     * @return void
     *
     */ 

    public function __construct($entity, $value) {

        $this->entity = $entity;
        $this->value = $value;            

    }




    /**
     * getEntity()
     *
     * @return String
     *
     */ 

    public function getEntity() {

        return $this->entity;           

    }




    /**
     * __construct()
     *
     * @return 
     *
     */ 

    public function getValue() {

        return $this->value;            

    }



}
