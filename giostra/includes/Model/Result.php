<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class Result{

  /**
   * @var boolean $result     
   */

    private $result;

  /**
   * @var string $message     
   */

    private $message;

  /**
   * @var INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE $value     
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
     * @param $bool Bool
     * @param $message String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return void
     *
     */ 

    public function __construct($bool, $message, $value=null) {
        $this->result = $bool;
        $this->message = $message;
        $this->value = $value;  
    }



    /**
     * getBoolean()
     *
     * @return Boolean
     *
     */ 

    public function getBoolean(){
        return $this->result;
    }



    /**
     * getMessage()
     *
     * @return String
     *
     */ 

    public function getMessage(){
        return $this->message;
    }



    /**
     * getValue()
     *
     * @return INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     *
     */ 

    public function getValue(){
        return $this->value;
    }



}