<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Security
 */

class Validate{


  /**
    * @var String $regularex       
    */

    private $regularex = array();

  /**
    * @var String $custommethod       
    */

    private $custommethod = array();  


  /**
    * @var Regular_Expression $INTEGER       
    */
    
    public static $INTEGER = "/^[0-9]{1,}/";

  /**
    * @var Regular_Expression $FLOAT       
    */

    public static $FLOAT = '/[^a-zA-Z0-9 "\'\?\-]/';

  /**
    * @var Regular_Expression $DOUBLE       
    */

    public static $DOUBLE = '/[^a-zA-Z0-9 "\'\?\-]/';

  /**
    * @var Regular_Expression $BOOLEAN       
    */

    public static $BOOLEAN = "/^[0-1]{1,1}$/";

  /**
    * @var Regular_Expression $DATE       
    */

    public static $DATE ="/^[0-9]{4}-[0-9]{2}-[0-9]{2}/"; 

  /**
    * @var Regular_Expression $DATETIME       
    */

    public static $DATETIME = "/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/";

  /**
    * @var Regular_Expression $TIMESTAMP       
    */

    public static $TIMESTAMP = "/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/";

  /**
    * @var Regular_Expression $TIME       
    */

    public static $TIME ="/^[0-9]{1,2}:[0-9]{2}:[0-9]{2}/"; 

  /**
    * @var Regular_Expression $TEXT       
    */

    public static $TEXT = "/[\x20-x7E]+/";

  /**
    * @var Regular_Expression $VARCHAR       
    */

    public static $VARCHAR = '/^([a-zA-Z0-9áÁàÀâÂéÉèÈêÊíÍìÌîÎóÓòÒôÔúÚùÙûÛ´`‘’?!#@\.\_\-,;:()\' ])+$/';

  /**
    * @var Regular_Expression $CHAR       
    */

    public static $CHAR = '/^([a-zA-Z0-9áÁàÀâÂéÉèÈêÊíÍìÌîÎóÓòÒôÔúÚùÙûÛ ])+$/';

  /**
    * @var Regular_Expression $FILE       
    */

    public static $FILE = "/^.+\.(jpeg|jpg|png|gif|bmp|zip|rar|txt|doc|docx|ppt|pptx|pdf)$/";

  /**
    * @var Regular_Expression $PASSWORD     
    */

    public static $PASSWORD = "/((?=.*[0-9])(?=.*[a-zA-Z]).{8,})/"; 

  /**
    * @var Regular_Expression $PREFIX       
    */

    public static $PREFIX = "/^[a-zA-Z]{1,4}[_]{1,1}/"; 

  /**
    * @var Regular_Expression $IP       
    */

    public static $IP = "/^([0-9]{1,3}\.){3}[0-9]{1,3}$/";

  /**
    * @var Regular_Expression $URL       
    */

    public static $URL = "/^http:\/\/(www\.)?[a-zA-Z0-9-]{3,}\.[a-zA-Z]{2,}(\/)?$/";  

  /**
    * @var Regular_Expression $EMAIL       
    */

    public static $EMAIL = '/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)+$/';

  /**
    * @var Regular_Expression $SQL       
    */

    public static $SQL = "/('(''|[^'])*')|(;)|(\b(ALTER|CREATE|DELETE|DROP|EXEC(UTE){0,1}|INSERT( +INTO){0,1}|MERGE|SELECT|UPDATE|UNION( +ALL){0,1})\b)/"; 



    /**
     * INPUT()
     *
     * @param Const $type
     * @param String $value 
     * @return boolean
     *
     */ 

    public static function INPUT($type, $value){

        try {
            if (property_exists(new Validate, $type)) 
                return preg_match(self::${$type}, $value);
            else return preg_match($type, $value);            
        } catch (Exception $e) {
            Log::Error($e); 
            return false;
        }


    } 


    /**
     * SetRegularExpression()
     *
     * @param String $entity
     * @param String $value      
     * @return void
     *
     */ 

    public function SetRegularExpression($entity, $value){
        $this->regularex[] = new Struct($entity,$value);
    }


    /**
     * SetCustomMethod()
     * @param String $entity
     * @param String $name
     * @return void
     *
     */ 

    public function SetCustomMethod($entity, $name){
        $this->custommethod[] = new Struct($entity,$name);
    }



    /**
     * getRegularExpression()
     *
     * @param String $name 
     * @return string
     *
     */ 

    public function getRegularExpression($name){

        for ($i = 0; $i < length($this->regularex); $i++){
            if (isset($this->regularex[$i]))
                if($this->regularex[$i]->getEntity() == $name)
                    return $this->regularex[$i]->getValue();
                    
        }
        return null;
    } 



    /**
     * getCustomMethod()
     *
     * @param String $name 
     * @return array
     *
     */ 

    public function getCustomMethod($name){
        for ($i = 0; $i < length($this->custommethod); $i++){
            if (isset($this->custommethod[$i]))
                if($this->custommethod[$i]->getEntity() == $name)
                    return $this->custommethod[$i]->getValue();
        }

        return null;

    } 

               
}



/**
 * PARAMETER()
 *
 * @param $name String
 * @param $type 
 *
 */ 

function PARAMETER($name,$type=null){

    try {
        /* Se esiste un tipo valido il tipo, altrimenti passo 
           l'imput come mi è stato fornito dalla get o da post */
        if ($type!=null) {     
            // Verifico l'esistenza della variabile di tipo
            if (property_exists(new Validate, $type)) {
                if (isset($_FILES[$name])) 

                    if(Validate::INPUT($type, $_FILES[$name]['name'])){
                         return $_FILES[$name];
                    }else{
                        Log::Warning(Language::$incorrectInput,array($name => $_FILES[$name]['name'])); 
                        return null;
                    } 

                elseif (isset($_POST[$name]))

                    if(Validate::INPUT($type, $_POST[$name])){
                         return $_POST[$name];
                    }else{
                        Log::Warning(Language::$incorrectInput,array($name => $_POST[$name])); 
                        return null;
                    } 

                elseif (isset($_GET[$name]))

                    if(Validate::INPUT($type, $_GET[$name])){
                        return $_GET[$name];
                    }else{
                        Log::Warning(Language::$incorrectInput,array($name => $_GET[$name]));
                        return null;
                    } 

                return null;
            }return null;

        }elseif (isset($_FILES[$name])) 
            return $_FILES[$name];
        elseif (isset($_POST[$name]))
            return $_POST[$name];
        elseif (isset($_GET[$name]))
            return $_GET[$name];
        return null;     

    } catch (Exception $e) {
        Log::Error($e); 
        return null;
    }




}













