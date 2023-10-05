<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class Entity{


    /**
      * @var string $name Generic name       
      */
    private $name;  

    /**
      * @var const $type Types definited of system      
      */     
    private $type;  

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
     * @param $type Constant
     * @param $option Array[]
     *
     */ 

    public function __construct($name, $type,$option) {
        
        $this->name = $name;
        $this->type = $type;
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
     * getType()
     *
     * @return String
     *
     */ 

    public function getType(){
        return $this->type;
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
