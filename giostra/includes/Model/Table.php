<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */


class Table{

  /**
   * @var array $file      
   */
    private $file;

  /**
   * @var array $Columns      
   */
    private $Columns;

  /**     
   * @var Doctrine $Query
   */    
    private $Query;

  /**
   * @var array $Button
   */    
    private $Button;

  /**
   * @var array $TdClass
   */    
    private $TdClass;



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
     * SetColumns()
     *
     * @param $array array[]
     * @example $array=array('id','name','surname','email')
     * @return void
     *
     */ 

    public function SetColumns($array){
        $this->Columns = $array;
    }




    /**
     * SetQuery()
     *
     * @param $query Doctrine
     * @example $query=Select()->From($Users)->Result()     
     * @return void
     *
     */ 

    public function SetQuery($query){
        $this->Query = $query;
    }


    /**
     * SetButton()
     *
     * @param $array array[]
     * @example $array=array('name'=>'Name_Button','link'=>'Link_button')
     * @return void
     *
     */ 

    public function SetButton($array){
        $this->Button = $array;
    }


    /**
     * SetTdCalss()
     *
     * @param $array array[] 
     * @example $array=array("nome"=>"hidden","anno_nascita"=>"hidden")     
     * @return void
     *
     */ 

    public function SetTdClass($array){
        $this->TdClass = $array;
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
     * getColumns()
     *
     * @return array[]
     * @example array('id','name','surname','email')     
     *
     */ 

    public function getColumns(){
        if (isset($this->Columns)) 
            return $this->Columns;
        else return null;
    } 


    /**
     * getQuery()
     *
     * @return Doctrine
     *
     */ 

    public function getQuery(){
        if (isset($this->Query)) 
            return $this->Query;
        else return "NotExist";
    } 


    /**
     * getButton()
     *
     * @return array[]
     * @example array('name'=>'Name_Button','link'=>'Link_button')     
     *
     */ 

    public function getButton(){
        if (isset($this->Button)) 
            return $this->Button;
        else return null;
    }      
  


    /**
     * getTdClass()
     *
     * @return array[]
     * @example array("nome"=>"hidden","anno_nascita"=>"hidden")      
     *
     */ 

    public function getTdClass(){
        if (isset($this->TdClass)) 
            return $this->TdClass;
        else return null;
    }  

}


