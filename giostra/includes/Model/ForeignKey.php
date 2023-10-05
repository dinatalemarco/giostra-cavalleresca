<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class ForeignKey{

    /**
      * @var string $name Generic name        
      */
    private $name;   

    /**
      * @var Object $object       
      */    
    private $object; 

    /**
      * @var array $option[]       
      */      
    private $option; 


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
     * @param $name String
     * @param $object Object
     * @param $option Array[]
     *
     */ 

    public function __construct($name, $object,$option="") {

        $this->name = $name;
        $this->object = $object;
        $this->option = $option;            
        

    }


    /**
     * getname()
     *
     * @return String
     *
     */ 

    public function getName(){
        return $this->name;
    }


    /**
     * getObject()
     *
     * @return Object
     *
     */ 

    public function getObject(){
        return $this->object;
    }


    /**
     * getOption()
     *
     * @return Array[]
     *
     */ 

    public function getOption(){
        return $this->option;
    }

}


