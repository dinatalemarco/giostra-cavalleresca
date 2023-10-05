<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */

 trait SystemPage{


    /**
     * CreateFileManager()
     *
     * @return void
     * @throws Exception Log Writes the error in the log
     *
     */ 

    public function CreateFileManager(){

      /* Creo i file PHP per la gestione delle tabelle */
      $this->CreateFilePHP();      
      /* Creo i file Javascript per la pre validazione delle form */
      $this->CreateFileJS();
      /* Creo i file html di gestione delle form */
      $this->CreateFileForm();


    }


    /**
     * CreateFileJS()
     *
     * @return void
     * @throws Exception Log Writes the error in the log
     *
     */ 

    private function CreateFileJS(){

      try { 

        /* Verifichiamo l'esistenza delle cartelle dove andremo ad inserire i file*/
        if(!is_dir("../modules/".$this->getModule()."/view/"))
          mkdir("../modules/".$this->getModule()."/view/", 0700);
        if(!is_dir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/"))
          mkdir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/", 0700);        
        if(!is_dir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/js/"))
          mkdir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/js/", 0700); 


        // Creo il file nei rispettivi moduli
        $file = "../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/js/".$this->getNameTable().".js";

        if (!file_exists($file)) {

          $createFile = fopen($file, "a"); 

          $resMethod= $resValidate=null;
          $Validate = new javascriptCode();

          for ($i= 0; $i < length($this->getEntityList()); $i++){
     
              if (!(isset($this->getEntityList()[$i]->getOption()['autoincrement']) 
                  && $this->getEntityList()[$i]->getOption()['autoincrement'])){

                  $resMethod[$i][0] = $this->getEntityList()[$i]->getName();

                  /* Verifichiamo se la nostra entity ha associta un espressione regolare,
                     in caso contrario settiamo un espressione di base */
                  $ExReg = $this->getObjValidators()
                                ->getRegularExpression($this->getEntityList()[$i]->getName()); 

                  if ($ExReg != null) 
                      $resMethod[$i][1] = $ExReg;
                  else $resMethod[$i][1] = Validate::${$this->getEntityList()[$i]->getType()};

                  $resValidate[$i][0] =$this->getEntityList()[$i]->getName();
                  if (isset($this->getEntityList()[$i]->getOption()[NOT_NULL])){
                      if ($this->getEntityList()[$i]->getOption()[NOT_NULL])
                          $resValidate[$i][1] = "true";
                      else  $resValidate[$i][1] = "false";
                  }else $resValidate[$i][1] = "false";

                  if (isset($this->getEntityList()[$i]->getOption()[length]))
                      $resValidate[$i][2] = $this->getEntityList()[$i]->getOption()[length];
                  else $resValidate[$i][2] = null;

              }                     
          }


          $Validate->injectCode('nameForm',$this->getNameTable());
          $Validate->injectCode('keyTable',$this->getKeyTable());
          $Validate->injectCode('method',$resMethod);
          $Validate->injectCode('validate',$resValidate);


          fwrite($createFile, $Validate->getJSCode());
          fclose($createFile);

        }

        
      } catch (Exception $e) {
          Log::Error($e);
      }

    }


    /**
     * CreateFileForm()
     *
     * @return void 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */


    private function CreateFileForm(){

    try {
            /* Setto la variabile di scrittura del codice a null */
            $code = null;

            /* Scorriamo la lista di entità */
            for ($i = 0; $i < length($this->getEntityList()); $i++) {

                $required=true;

                $Name = $this->getEntityList()[$i]->getName();
                $Type = $this->getEntityList()[$i]->getType();
                $Option = $this->getEntityList()[$i]->getOption();


                /* Viene settata a false la variabile $required se questa è una foreignKey
                   poichè non deve essere rappresentata in modo tradizionale */
                for ($j=0; $j < length($this->getForeignKeyList()); $j++) { 
                    for ($b=0; $b < length($this->getForeignKeyList()[$j]->getName()) ; $b++) { 
                        if ($this->getForeignKeyList()[$j]->getName()[$b] == $Name) {
                            /* Per evitare di scorrere nuovamente la lista delle foreignkey e
                               per tenere traccia dell'elemento che voglio creare inseriamo in
                               un array l'oggetto a cui appartiene l'elemento e la sua posizione */
                            $uuoden = new fragmentCode("component/SelectBox"); 
                            $required=false;
                        }
                    }
                }


                if ($required) {

                    if ($Type == TIME) 
                        $uuoden = new fragmentCode("component/TimeBox"); 
                    elseif ($Type == DATE) 
                        $uuoden = new fragmentCode("component/DateBox");
                    elseif ($Type == DATETIME || $Type == TIMESTAMP) 
                        $uuoden = new fragmentCode("component/DateTimeBox");                  
                    elseif ($Type == TEXT) 
                        $uuoden = new fragmentCode("component/TextArea");
                    elseif ($Type == BOOLEAN) 
                        $uuoden = new fragmentCode("component/SelectBoolean");   
                    elseif ($Type == FILE) 
                        $uuoden = new fragmentCode("component/FileInput");                        
                          
                    /* Voglio visualizzare tutte le entità di tipo Textbox ad eccezione dei campi autoincrement, nelle form per la costruzione di un nuovo elemento, nelle form di edit voglio visualizzare anche gli autoincrement come elementi statici nella form ( $param != null )*/                      
                    elseif ($Type==INTEGER || $Type==FLOAT || $Type==DOUBLE || $Type==VARCHAR || $Type==CHAR)
                            if (!(isset($Option['autoincrement']) && $Option['autoincrement'])) 
                                $uuoden = new fragmentCode("component/TextBox");
                            else $uuoden = new fragmentCode("component/StaticControl");
                         
                }

                $uuoden->injectCode("entity",$Name);
                $uuoden->injectCode("description",null);
                $uuoden->injectCode("class",null); 
                $uuoden->injectCode("value","{\$".$Name."}");                   
                $code .= $uuoden->get(); 
                             
            }     


        $uuoden = new fragmentCode("component/Form");
        $uuoden->injectCode("Page","{\$Page}"); // nome della pagina
        $uuoden->injectCode("action","{\$action}"); // azione della pagina
        $uuoden->injectCode("key","{\$key}");        

        $uuoden->injectCode("NameService",$this->getNameService());

        $uuoden->injectCode("random_sub","{\$random_sub}");
        $uuoden->injectCode("module",$this->getModule());
        $uuoden->injectCode("service",$this->getNameTable());
        $uuoden->injectCode("composePage",$code);


        /* Verifichiamo l'esistenza delle cartelle dove andremo ad inserire i file*/
        if(!is_dir("../modules/".$this->getModule()."/view/"))
          mkdir("../modules/".$this->getModule()."/view/", 0700);
        if(!is_dir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/"))
          mkdir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/", 0700);        
        if(!is_dir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/html/"))
          mkdir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/html/", 0700);   
        if(!is_dir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/html/Form/"))
          mkdir("../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/html/Form/", 0700);                      

        // Creo il file nei rispettivi moduli
        $file = "../modules/".$this->getModule()."/view/".Config::getTemplateSystem()."/html/Form/".$this->getNameTable().".html";

        if (!file_exists($file)) {
          $createFile = fopen($file, "a"); 
          fwrite($createFile, $uuoden->get());
          fclose($createFile);
        }

    } catch (Exception $e) {
        Log::Error($e);
    }


    }




    /**
     * CreateFilePHP()
     *
     * @return void
     * @throws Exception Log Writes the error in the log
     *
     */ 

    private function CreateFilePHP(){

    try {

    $key_tabel= $this->getKeyTable();

    $insert=$delete=null;
    $spaces = ",\r\n\t\t\t\t\t\t\t\t";
    for ($i=$j=$t=0 ; $i < length($this->getEntityList()); $i++) {

        if (!(isset($this->getEntityList()[$i]->getOption()['autoincrement']) && $this->getEntityList()[$i]->getOption()['autoincrement'])) {
            if ($j!=0) $insert .= $spaces;
                $insert .='\''.$this->getEntityList()[$i]->getName().'\' => PARAMETER(\''.$this->getEntityList()[$i]->getName().'\')'; 
            $j++; 
        }  

        if ($t < length($key_tabel))
        if ($key_tabel[$t] == $this->getEntityList()[$i]->getName()) {
            if ($t!=0) $delete .=",";
            $delete .='\''.$key_tabel[$t].'\' => PARAMETER(\''.$key_tabel[$t].'\')';
            $t++;
        }
        
    }


    /* Verifichiamo l'esistenza della cartella controller, in caso non esista verrà cretata */
    if(!is_dir("../modules/".$this->getModule()."/controller/"))
        mkdir("../modules/".$this->getModule()."/controller/", 0700);
   

    // Creo il file nei rispettivi moduli
    $file = "../modules/".$this->getModule()."/controller/".$this->getNameTable().".php";

    if (!file_exists($file)) {

    $createFile = fopen($file, "a"); 

    fwrite($createFile, 
'<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package '.$this->getModule().'
 */

class Module{
  

/**
  * @var OutLine $outline       
  */
  private $outline;

/**
  * @var fragmentCode $jsBase       
  */  
  private $jsBase;

/**
  * @var Model $obj       
  */
  private $obj;

/**
  * @var Session $session       
  */  
  private $session;

/**
  * @var array $key       
  */  
  private $key;

/**
  * @var array $value       
  */  
  private $value;


  public function index($action){

    $this->key =   array('.$delete.');
    $this->value = array('.$insert.'); 

    $this->obj = new '.get_class($this).'();
    $this->session = new SessionAdmin();

    $this->outline = new OpenOutLine(__System__);
    GraphicBase::Admin($this->outline);
    $this->jsBase = new fragmentCode("jsBase");

    $this->outline->injectCode("body", $this->$action());

    if ($action == "add" || $action == "edit") 
      $this->jsBase->injectCode("Emti_Validate", $this->obj->GetJsCode());    

    $this->outline->injectCode(\'jsBase\',$this->jsBase->get());
    $this->outline->closeOutline();


  }
  

/**
  * view()
  *
  * @return string
  *
  */

  public function view(){
    /* Visualizziamo l\'elenco presente nel database  */
    return $this->obj->Table();
  }

/**
  * add()
  *
  * @return string
  *
  */

  public function add(){
    return $this->obj->Form(null, $this->session); 
  }

/**
  * edit()
  *
  * @return string
  *
  */

  public function edit(){
    return $this->obj->Form($this->key, $this->session);
  }

/**
  * insert()
  *
  * @return string
  *
  */

  public function insert(){
    /* Viene richiamato il numero dato random alla form */
    $N_FORM = PARAMETER(\'random_sub\',VARCHAR);
    return $this->getMessage($this->obj->Insert($this->value, $this->session, $N_FORM));
  }

/**
  * update()
  *
  * @return string
  *
  */

  public function update(){
    /* Viene richiamato il numero dato random alla form */
    $N_FORM = PARAMETER(\'random_sub\',VARCHAR);
    return $this->getMessage($this->obj->Update($this->value, $this->key, $this->session, $N_FORM));
  }

/**
  * delete()
  *
  * @return string
  *
  */

  public function delete(){
    return $this->getMessage($this->obj->Delete($this->key));
  }

/**
  * getMessage()
  *
  * @return string
  *
  */

  public function getMessage($res){

      if ($res->getBoolean()) 
        $msg = new fragmentCode(\'component/Message\');
      else $msg = new fragmentCode(\'component/MessageError\');
          
      $msg->injectCode("message",$res->getMessage());

      /* Se non è attivo il controllo cross site request forgery faccio un redirect dopo 3 secondi */
      if (!$this->session->GetCSRF())
        header("Refresh: 3; url=".Config::getRootSite()."/administrator/'.$this->getModule().'/'.$this->getNameTable().'/view"); 

      return $msg->get().$this->view();


  }

}
                         
?>');


    fclose($createFile);

    }
        
    } catch (Exception $e) {
        Log::Error($e);
    }


    }  





}