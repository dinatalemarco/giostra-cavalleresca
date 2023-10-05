<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */

class Model {


    /**
      * @var String $module       
      */

    private $module = null;

    /**
      * @var String $service       
      */

    private $service = null;

    /**
      * @var String $table       
      */

    private $table = null; 

    /**
      * @var Table $definitionTable       
      */

    private $definitionTable = null;

    /**
      * @var Form $definitionForm       
      */

    private $definitionForm = null;

    /**
      * @var Validate $definitionValidators       
      */

    private $definitionValidators = null;

    /**
      * @var array $primaryKey       
      */

    private $primaryKey = array();

    /**
      * @var array $unique       
      */

    private $unique = array();

    /**
      * @var array $entity Entity List      
      */

    private $entity = array();  

    /**
      * @var array $foreignKey       
      */

    private $foreignKey = array();


    /**
     * __call()
     *
     * @param $name String
     * @param $argv array
     *
     */

    public function __call($name, $argv){ 
        Log::Error("Fatal error: Call to undefined method ".get_class($this)."::".$name."()");
        return null;
    }


    /**
     * SetTabel()
     *
     * @param $specific Table
     * @return void
     *
     */

    public function SetTable($specific) {

        $this->definitionTable = $specific;

    } 

    /**
     *
     * @param $specific array[]
     * @return void     
     *
     */

    public function SetForm($specific) {

        $this->definitionForm = $specific;

    }   

    /**
     *
     * @param $specific array[]
     * @return void 
     *
     */

    public function SetValidators($specific) {

        $this->definitionValidators = $specific;

    }   

    /**
     * SetModule
     *
     * @param $name String
     * @return void 
     *
     */

    public function SetModule($name) {

        $this->module = $name;

    }

    /**
     * SetNameService
     *
     * @param $name string
     * @return void 
     *
     */

    public function SetNameService($name) {

        $this->service = $name;

    }

    /**
     * NewTable prende un paramanetro in input che ci permette di definire
     * il nome della tabella che stiamo creando.
     *
     * @param $name String
     * @return void 
     *
     */

    public function NewTable($name) {

        $this->table = $name;

    }

    /**
     * 
     *
     * @param $name String
     * @param $type Constant INT | FLOAT | DOUBLE | BOOLEAN | DATE | DATETIME | TIMESTAMP | TIME | TEXT | VARCHAR | CHAR | FILE
     * @param $option array[]
     * @return void 
     *
     * $name: nome dell'entità, $type: tipo dell'entità, $option: opzioni dell'entità
     */    

    public function NewEntity($name, $type , $option = array(NOT_NULL => false)) {

        $this->entity[] = new Entity($name,$type,$option);

    }

    /**
     * PrimaryKey prende in input un paramentro:
     *
     * @param $list array[]
     * @return void 
     *
     * $list elenco delle chiavi prese in input
     */     

    public function PrimaryKey($list) {

        $this->primaryKey = $list;

    }

    /**
     * ForeignKey prende in input tre paramentri:
     *
     * @param $entity array[]
     * @param $obj object
     * @param $option array[]
     * @return void 
     *
     * $entity: prende in input un elenco di chiavi 
     * $obj: prende l'oggetto che si vuole referenziare
     * $option: prende un array di opzioni
     */      

    public function ForeignKey($entity, $obj, $option = array(OnDELETE => CASCADE)){

        $this->foreignKey[] = new ForeignKey($entity, $obj , $option);

    }

    /**
     * Unique prende in input un paramentro:
     *
     * @param $name array[]
     * @return void 
     *
     * $name: prende in input un elenco di elementi che voglio rendere uniche
     */      

    public function Unique($name) {

        $this->unique = $name;

    }   

    /**
     * getObjTable()
     *
     * @return Table
     *
     */       

    public function getObjTable(){

        if ($this->definitionTable != null) 
            return $this->definitionTable; 
        else return new Table();
    } 

    /**
     * getObjForm()
     *
     * @return Form
     *
     */       

    public function getObjForm(){

        if ($this->definitionForm != null)
            return $this->definitionForm;                         
        else return new Form();
    } 

    /**
     * getObjValidators()
     *
     * @return Validate
     *
     */       

    public function getObjValidators(){

        if ($this->definitionValidators != null) 
            return $this->definitionValidators;                       
        else return new Validate();
    } 

    /**
     * getModule()
     *
     * @return String
     *
     */       

    public function getModule(){

        return $this->module; 

    } 

    /**
     * getNameService()
     *
     * @return string
     *
     */       

    public function getNameService(){

        if ($this->service != null) 
            return $this->service; 
        else return $this->getNameTable();

    } 

    /**
     * getNameTable()
     *
     * @return string
     *
     */       

    public function getNameTable(){

        return $this->table; 
 
    }   

    /**
     * getNameDatabaseTable()
     *
     * @return string
     *
     */       

    public function getNameDatabaseTable(){ 
        $component = strtolower($this->getModule());
        return Config::getPrefixTable().$component."_".$this->getNameTable();
    } 

    /**
     * getEntityUnique()
     *
     * @return array[]
     *
     */       

    public function getEntityUnique(){

        return $this->unique;

    }

    /**
     * getForeignKeyList()
     *
     * @return array
     *
     */       

    public function getForeignKeyList(){

        return $this->foreignKey;

    }  
  

    /**
     * getEntityList()
     *
     * @return array
     *
     */       

    public function getEntityList(){

        return $this->entity;

    } 

    /**
     * getKeyTable()
     *
     * @return array[]
     *
     */     

    public function getKeyTable(){

        return $this->primaryKey;

    }

    /**
     * getTypeTable()
     *
     * @param $EntityType String
     * @return array[]
     *
     */     

    public function getTypeTable($EntityType){
        $array = null;
        for ($i=$cont=0; $i < length($this->getEntityList()) ; $i++) {  
            if ($this->getEntityList()[$i]->getType() == $EntityType) {
                $array[$cont] = $this->getEntityList()[$i]->getName();
                $cont++;
            }     
        }
        return $array;  
    }




    /**
     * getControlInformation()
     *
     * @return array[]
     *
     */

    private function getControlInformation($NameEntity) {

        try {

            $cont=0;
            $InfoEntity = null;
            $ParameterNotNull = false;

            /* Cerchiamo l'entità passata in input così da restituire tutte le informazioni
               di validazione legate a quest'ultima */
            for ($i=0; $i < length($this->getEntityList()) ; $i++) { 
                if ($this->getEntityList()[$i]->getName() == $NameEntity ) {

                $Name = $this->getEntityList()[$i]->getName();
                $Type = $this->getEntityList()[$i]->getType();
                $Option = $this->getEntityList()[$i]->getOption();

                $Regular_Expression = $this->getObjValidators()->getRegularExpression($Name); 
                $Function_Custom  = $this->getObjValidators()->getCustomMethod($Name);

                    /* Verifico se il parametro può essere nullo o meno*/
                    if (isset($Option[NOT_NULL]))
                        $ParameterNotNull = $Option[NOT_NULL];

                        // Verifico se il parametro può essere nullo o meno
                        $InfoEntity["NAME_PARAMETER"] = $Name;
                        $InfoEntity["PARAM_NOT_NULL"] = $ParameterNotNull;

                    /* Verifico il metodo di validazione dell'imput, se non esiste un espressiore 
                        regolare associata all'entity in esame viene eseguita una validazione di
                        base legata al tipo dichiarato in fase di creazione del database, altrimenti
                        viene eseguita la validazione con l'espressione regolare associata alla entity*/

                        if ($Regular_Expression != null || $Function_Custom != null){ 
                            if ($Regular_Expression != null) 
                                $InfoEntity["EXPRE_VALIDATE"] = $Regular_Expression;
                            
                            if ($Function_Custom != null) 
                                $InfoEntity["CUSTOM_VALIDATE"] = $Function_Custom;
                    
                        }else $InfoEntity["METOD"] = $Type;
                        

                }
            }

            return $InfoEntity;

            
        } catch (Exception $e) {
            Log::Error($e);
            return array();
        }

    } 



    /**
     * Validate()
     *
     * @param $InfoEntity String
     * @param $value   
     * @return Result
     *
     */

    private function Validate($InfoEntity,$value) {


        try {
            
           $control = true;

            for ($i=0; $i < length($InfoEntity) && $control ; $i++){ 
                /* Verifico se il parametro che devo validare sia obbligatorio o se non è nullo, perchè se il parametro non è obbligatorio ed è nullo non lancio nessun controllo */
                if ($InfoEntity[$i]["PARAM_NOT_NULL"] || $value[$InfoEntity[$i]["NAME_PARAMETER"]]!=null) {
                    // Controllo di defaul dei tipi
                    if (isset($InfoEntity[$i]["METOD"])){ 
                        $metod = $InfoEntity[$i]["METOD"];
                        $control=Validate::INPUT($InfoEntity[$i]["METOD"],
                                                 $value[$InfoEntity[$i]["NAME_PARAMETER"]]); 
                    }
                    //Verifico la presenza di un espressione regolare
                    if(isset($InfoEntity[$i]["EXPRE_VALIDATE"])) 
                        $control = Validate::INPUT($InfoEntity[$i]["EXPRE_VALIDATE"], 
                                                   $value[$InfoEntity[$i]["NAME_PARAMETER"]]);

                    if (isset($InfoEntity[$i]["CUSTOM_VALIDATE"])) {

                        $CustomFunction = $InfoEntity[$i]["CUSTOM_VALIDATE"];
                        // Verifichiamo se la funzione inserita dall'utente è realmente eistsente
                        if (function_exists($CustomFunction)) {
                            $control = $CustomFunction($value[$InfoEntity[$i]["NAME_PARAMETER"]]);
                        }else{
                            $message = Language::$errorNonexistentFunction." "
                                                    .$InfoEntity[$i]["CUSTOM_VALIDATE"];
                            Log::Error($message);                        
                            return new Result(false,$message);
                        }

                    }
                                
                    
                    if (!$control){
                        $message =  Language::$errorFormValidation;
                        $ValError = array("Field" =>  $InfoEntity[$i]["NAME_PARAMETER"], 
                                          "Value" =>  $value[$InfoEntity[$i]["NAME_PARAMETER"]]); 

                        Log::Error($message,$ValError);
                        return new Result(false, $message."<br>Field: ".$InfoEntity[$i]["NAME_PARAMETER"]);
                    }
       

                }// Fine controllo campo nullo
            } // Fine ciclo for   

            return new Result(true, Language::$success);

        } catch (Exception $e) {
            Log::Error($e);
            return Result(false, $e->getMessage());
        }

    } 




    /**
     * ValidateParameter()
     *
     * @param $parameter String
     * @param $value    
     * @return Result
     *
     */

    public function ValidateParameter($parameter,$value) {


        try {
            
            /* Richiamiamo le informazioni che permettono di validare il parametro */
            $InfoEntity = array($this->getControlInformation($parameter)); 
                
            /* Verifico se la guardia è nulla significa che abbiamo inserito un parametro 
               non corretto */
            if ($InfoEntity!=null) {
                $ValueParameter = array($parameter => $value);
                return $this->Validate($InfoEntity,$ValueParameter);
            }else{
                $message =  Language::$checkParameter;
                Log::Error($message,array('Parameter'=> $parameter,'Value' => $value));
                return new Result(false,$message);                
            }

        } catch (Exception $e) {
            Log::Error($e);
            return Result(false, $e->getMessage());
        }


    }  



    /**
     * ValidateKey()
     *
     * @param $value Array[]    
     * @return Result
     *
     */

    public function ValidateKey($value) {


        try {

            if (is_array($value)) {
            
                $InfoEntity = array();
                $KeyTable = $this->getKeyTable();

                for ($i=$cont=0; $i < length($this->getEntityList()) ; $i++){ 
                    // Blocco che permette la verifica di un tipo di dato chiave
                    for ($j=0; $j < length($KeyTable); $j++){ 
                        if ($KeyTable[$j] == $this->getEntityList()[$i]->getName()){ 
                        /* Otteniamo le informazioni per permettere la validazioni dei parametri di tipo chiave,
                           quindi per ogni entità che compone la chiave invochiamo la getControlInformation
                           e concateniamo tutti i risultati in un solo array */  
                        $NameEntity = $this->getEntityList()[$i]->getName();                           
                        $InfoEntity = array_merge($InfoEntity,array($this->getControlInformation($NameEntity)));  
                        }  
                    } 
                }

                return $this->Validate($InfoEntity,$value);  

            }

            $message =  Language::$errorNotArray." ".$value."<br>".
                        Language::$formArray." array('key' => '".$value."')";
            Log::Error($message);
            return new Result(false,$message);              
            
        } catch (Exception $e) {
            Log::Error($e);
            return Result(false, $e->getMessage());            
        }           
        

    }  


    /**
     * ValidateForm()
     *
     * @param $value Array[]    
     * @return Result
     *
     */

    public function ValidateForm($value) {

        try {

            if (is_array($value)) {
            $InfoEntity = array();

                for ($i=$cont=0; $i < length($this->getEntityList()) ; $i++){  
                    $Option = $this->getEntityList()[$i]->getOption();
                    if (!(isset($Option['autoincrement']) && $Option['autoincrement'])){
                        $NameEntity = $this->getEntityList()[$i]->getName();                      
                        $InfoEntity = array_merge($InfoEntity,array($this->getControlInformation($NameEntity))); 
                    }
                }       

                return $this->Validate($InfoEntity,$value);
            }

            $message =  Language::$errorNotArray." ".$value."<br>".
                        Language::$formArray." array('key' => '".$value."')";
            Log::Error($message);
            return new Result(false,$message);            
            
        } catch (Exception $e) {
            Log::Error($e);
            return Result(false, $e->getMessage());     
        } 

    }  


    /**
     * GetJsCode()
     *
     * @return String
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    public function GetJsCode(){


        try {
            
        $resMethod= $resValidate=null;
        $Validate = new javascriptCode();

        $Validate->injectCode('modules',$this->getModule());
        $Validate->injectCode('service',$this->getNameService());
        $Validate->injectCode('template',Config::getTemplateSystem());


        return $Validate->get();

        } catch (Exception $e) {
            Log::Error($e);
        }
                

    }


}
