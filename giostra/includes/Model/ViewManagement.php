<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */



trait ViewManagement{

    /**
     * CustomBox()
     *
     * @param $file String
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log
     *
     */

   
    private function CustomBox($file, $ObjForm, $name, $value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            if (!strpos($file, ".")) 
                $uuoden = new fragmentCode("component/custom/".$file.".html");
            else $uuoden = new fragmentCode("component/custom/".$file);

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get();  

        } catch (Exception $e) {
            Log::Error($e);
        } 

    }



    /**
     * StaticControlBox()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log
     *
     */

   
    private function StaticControlBox($ObjForm, $name, $value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden = new fragmentCode("component/StaticControl.html");

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get();  

        } catch (Exception $e) {
            Log::Error($e);
        } 

    }




    /**
     * TimeBox()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log     
     *
     */

   
    private function TimeBox($ObjForm, $name, $value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden = new fragmentCode("component/TimeBox"); 

            if ($value == null) $value = date("H:i:s");

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get(); 

        } catch (Exception $e) {
            Log::Error($e);
        }


    }



    /**
     * DateBox()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    private function DateBox($ObjForm, $name, $value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden = new fragmentCode("component/DateBox");

            if ($value == null) $value = date("Y-m-d");      

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get(); 

        } catch (Exception $e) {
            Log::Error($e);
        }        

    }



    /**
     * DateTimeBox()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    private function DateTimeBox($ObjForm, $name, $value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden = new fragmentCode("component/DateTimeBox");

            if ($value == null) $value = date("Y-m-d H:i:s");        

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get(); 

        } catch (Exception $e) {
            Log::Error($e);
        }        

    }



    /**
     * SelectBoolean()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String  
     * @throws Exception Log It does not return the element but writes the error in the log    
     *
     */

    private function SelectBoolean($ObjForm, $name ,$value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden= new fragmentCode("component/SelectBoolean");

            if ($value == null) $value = '0';     

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get(); 
            
        } catch (Exception $e) {
            Log::Error($e);
        }

    }    


    /**
     * SelectBox()
     *
     * @param $ObjForm Form
     * @param $element Array
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    private function SelectBox($ObjForm, $element ,$value=null){


    try {

        /* L'array element contiene due paramentri, un oggetto e un indice che ci evita
           di dover cercare nuovamente l'elemento che si desidera creare */
        $Object = $element['Object']->getObject(); // Oggetto da referenziare
        $Position = $element['Position']; // Posizione dell'elemento da creare
        $NameForeignKey = $element['Object']->getName()[$Position];
        $ElementKey = $Object->getKeyTable()[$Position];

        $column = array($ElementKey); // array di elementi da cercare nel database

        // Verifico l'esistenza di un alias così da estrarlo dal database
        if($ObjForm->getAlias($NameForeignKey))
            $column = array_merge($column, $ObjForm->getAlias($NameForeignKey));

        /* Verifico l'esistenza di una query custom sulla select */
        $QuerySelect = $ObjForm->getConditionSelect($NameForeignKey);

        $Description = $ObjForm->getDescription($NameForeignKey); 
        $Class = $ObjForm->getClass($NameForeignKey);

        /* verifichiamo se bisogna effettuare una query con condizione su un blocco select */
        if ($QuerySelect) 
            $ValueForeignKey = $Object->Select($column)
                                      ->Where($QuerySelect[0],$QuerySelect[1])
                                      ->Result();
        else $ValueForeignKey = $Object->Select($column)->Result();

        /* Modello l'array dei risultati in modo da suddividere l'alias definito con il nome 
           della entity */
        $ElementResult=null;
        for ($i=0; $i < length($ValueForeignKey) ; $i++) { 
            $ElementResult[$i]['element'] = $ValueForeignKey[$i][$ElementKey];
            $ListAlias = $ObjForm->getAlias($NameForeignKey);
            for ($j=0; $j < length($ListAlias) ; $j++) { 
                if (!isset($ElementResult[$i]['alias']))
                   $ElementResult[$i]['alias'] = $ValueForeignKey[$i][$ListAlias[$j]];
                else $ElementResult[$i]['alias'] = $ElementResult[$i]['alias']." ".$ValueForeignKey[$i][$ListAlias[$j]];
            }
        }


        /* Verifico se questo campo deve essere mostrato, se non deve essere mostrato non 
           verrà attaccato alla form */
        if (!$ObjForm->getVisibility($NameForeignKey)) return null;

        $uuoden = new fragmentCode("component/SelectBox");
        $uuoden->injectCode("entity",$NameForeignKey);
        $uuoden->injectCode("element",$ElementResult); 
        $uuoden->injectCode("description",$Description);
        $uuoden->injectCode("class",$Class);
        $uuoden->injectCode("value",$value);

        return $uuoden->get();


    } catch (Exception $e) {
        Log::Error($e);
    }


    }



    /**
     * TextBox()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    private function TextBox($ObjForm, $name ,$value=null){


        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden= new fragmentCode("component/TextBox");

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get();  

        } catch (Exception $e) {
            Log::Error($e);
        }        

    }


    /**
     * TextArea()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value INT|FLOAT|DOUBLE|BOOLEAN|DATE|DATETIME|TIMESTAMP|TIME|TEXT|VARCHAR|CHAR|FILE
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    private function TextArea($ObjForm, $name ,$value=null){

        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);

            $uuoden= new fragmentCode("component/TextArea");

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);
            $uuoden->injectCode("value",$value);

            return $uuoden->get();  

        } catch (Exception $e) {
            Log::Error($e);
        }

    }




    /**
     * AddFile()
     *
     * @param $ObjForm Form
     * @param $name String
     * @param $value FILE
     * @return String
     * @throws Exception Log It does not return the element but writes the error in the log  
     *
     */

    private function AddFile($ObjForm, $name ,$value=null){


        try {

            if (!$ObjForm->getVisibility($name)) return null;

            $Description = $ObjForm->getDescription($name);        
            $Class = $ObjForm->getClass($name);
     
            $uuoden= new fragmentCode("component/FileInput");

            /* Bisogna ricostruire il path per la visualizzazione dell'immagine */
            if ($value != null) {
                /* Verifichiamo che sia stata settata una cartella custom, scorro tutte le entity fino a trovare
                   quella cercata, una volta trovato estraggo le option */
                for ($i=0; $i < length($this->getEntityList()) ; $i++) { 
                 if ($this->getEntityList()[$i]->getName() == $name) {

                    $path = "modules/".$this->getModule()."/upload/";    
                    $Option = $this->getEntityList()[$i]->getOption();

                    if (isset($Option['CUSTOM_DIR'])) 
                        $uuoden->injectCode("value", $path.$Option['CUSTOM_DIR']."/".$value);
                    else $uuoden->injectCode("value", $path.$value);
                    
                 }
                }

            }else{
                $uuoden->injectCode("value",$value);
            }

            $uuoden->injectCode("entity",$name);
            $uuoden->injectCode("description",$Description);
            $uuoden->injectCode("class",$Class);

            return $uuoden->get();   

        } catch (Exception $e) {
            Log::Error($e);
        }

    }




    /**
     * Form()
     *
     * @param $param array
     * @param $session Session
     * @param $ObjForm Form
     * @return String 
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */


    public function Form($param=null, $session=null, $ObjForm=null){

    try {
     
        if ($ObjForm == null) 
            $ObjForm = $this->getObjForm();


        $code = $elemEdit = $keyEdit = $value = null;

            // Verifico se devo creare una form per una insert o per una edit
            if ($param != null) {       
                $guard = null;
                $key = $this->getKeyTable();

                for ($i=0; $i < length($key) ; $i++) { 
                    if ($i != 0 && $i != length($key)){ 
                       $guard .=" && "; 
                       $keyEdit .= "&";
                    }
                    $guard .= $key[$i]." = :".$key[$i];
                    $keyEdit .= $key[$i]."=".$param[$key[$i]];
                }
                $elemEdit = $this->Select()
                                 ->Where($guard ,$param)
                                 ->Result();
            }
        



            /* Scorriamo la lista di entità */
            for ($i = 0; $i < length($this->getEntityList()); $i++) {
                $required=true;

                $Name = $this->getEntityList()[$i]->getName();
                $Type = $this->getEntityList()[$i]->getType();
                $Option = $this->getEntityList()[$i]->getOption();

                /* Setto la variabile $value in modo da dare null se si tratta di una pagina 
                   che identifica un nuovo inserimento altrimenti il valore da modificare */
                if ($elemEdit!= null) 
                     $value = $elemEdit[0][$Name];
                else $value =null;

                /* Viene settata a false la variabile $required se questa è una foreignKey
                   poichè non deve essere rappresentata in modo tradizionale */
                for ($j=0; $j < length($this->getForeignKeyList()); $j++) { 
                    for ($b=0; $b < length($this->getForeignKeyList()[$j]->getName()) ; $b++) { 
                        if ($this->getForeignKeyList()[$j]->getName()[$b] == $Name) {
                            /* Per evitare di scorrere nuovamente la lista delle foreignkey e
                               per tenere traccia dell'elemento che voglio creare inseriamo in
                               un array l'oggetto a cui appartiene l'elemento e la sua posizione */
                            $Element = array("Object"   => $this->getForeignKeyList()[$j],
                                             "Position" => $b);
                            $code .= $this->SelectBox($ObjForm, $Element, $value);
                            $required=false;
                        }
                    }
                }


                if ($required) {
                    /* Verifico se si vuole usare una Custom Box */
                    $CustomBox = $ObjForm->getCustomBox($Name);
                    if ($CustomBox != null)
                        $code .= $this->CustomBox($CustomBox, $ObjForm, $Name ,$value);
                    elseif ($Type == TIME) 
                        $code .= $this->TimeBox($ObjForm, $Name, $value);  
                    elseif ($Type == DATE) 
                        $code .= $this->DateBox($ObjForm, $Name ,$value);
                    elseif ($Type == DATETIME || $Type == TIMESTAMP) 
                        $code .= $this->DateTimeBox($ObjForm, $Name, $value);                  
                    elseif ($Type == TEXT) 
                        $code .= $this->TextArea($ObjForm, $Name, $value);
                    elseif ($Type == BOOLEAN) 
                        $code .= $this->SelectBoolean($ObjForm, $Name, $value);    
                    elseif ($Type == FILE) 
                        $code .= $this->AddFile($ObjForm, $Name, $value);                         
                          
                 /* Voglio visualizzare tutte le entità di tipo Textbox ad eccezione dei campi autoincrement, nelle form per la costruzione di un nuovo elemento, nelle form di edit voglio visualizzare anche gli autoincrement come elementi statici nella form ( $param != null )*/                      
                 elseif ($Type==INTEGER || $Type==FLOAT || $Type==DOUBLE || $Type==VARCHAR || $Type==CHAR)
                   if (!(isset($Option['autoincrement']) && $Option['autoincrement'])) 
                        $code .= $this->TextBox($ObjForm, $Name, $value);  
                    elseif ($param != null) 
                           $code .= $this->StaticControlBox($ObjForm, $Name, $value); 
                         
                }
                
                             
            }         


        /* Verifichiamo se è stato definito un file custom per la definizione della form */
        if ($ObjForm->getCustomFile())
            $uuoden= new fragmentCode("component/custom/".$ObjForm->getCustomFile());
        else $uuoden= new fragmentCode("component/Form");

        $action = null;
        if ($param != null){ 
             $uuoden->injectCode("Page","Edit"); // nome della pagina
             $uuoden->injectCode("action","update"); // azione della pagina
             $uuoden->injectCode("key",$keyEdit);
             $action = "edit";
        }else{ 
            $uuoden->injectCode("Page","Add"); // nome della pagina
            $uuoden->injectCode("action","insert"); // azione della pagina
            $action = "add";
        }


        $uuoden->injectCode("NameService",$this->getNameService());
      


        $Random_N = Random(100);
        // Verifico se è attiva la sottomissione unica delle form
        if ($session != null && $session->GetCSRF())
            $session->NewFormCSRF($Random_N,$this->getNameTable(),$action);

        $uuoden->injectCode("random_sub",$Random_N);
        $uuoden->injectCode("module",$this->getModule());
        $uuoden->injectCode("service",$this->getNameTable());
        $uuoden->injectCode("composePage",$code);
        
        return $uuoden->get(); 

    } catch (Exception $e) {
        Log::Error($e);
    }


    }




    /**
     * Table()
     *
     * @param $ObjectTable Table
     * @param $customFileTable String
     * @return String
     * @throws Exception Log It does not return the element but writes the error in the log 
     *
     */

    public function Table($ObjectTable=null){

        try {

        $entity=null;
        $key=null;
        $key_entity=null;
        $column=array("*");

        $key_table = $this->getKeyTable();  // Recupero le chiavi della mia tabella

        /* Verifico se nella dichiarazione dell'oggetto ho dichiarato le colonne che
           desidero mostrare, se non ho dichiarato nulla mostro tutta la tabella, se
           ho dichiarato degli elementi e tra questi non ci sono le chiavi, le aggiungo io */

           /* Verifico che non si sta passando un oggetto custom al metodo */
           if($ObjectTable==null)
              $ObjectTable = $this->getObjTable();


            if ($ObjectTable->getColumns()){ 
                $control=false;
                $arrayKey = null;
                $col = $ObjectTable->getColumns();
                for ($i=0; $i < length($key_table) ; $i++) { 
                    for ($j=0; $j < length($col) ; $j++)  
                      if ($key_table[$i] == $col[$j]) 
                        $control = true;
                        
                    if (!$control) 
                     if ($arrayKey == null) 
                          $arrayKey = array($key_table[$i]);
                     else $arrayKey = array_merge($arrayKey, array($key_table[$i]));
                    
                }
                if ($arrayKey == null) 
                     $column = $col;
                else $column = array_merge($arrayKey, $col);
            }

                // Verifico l'esistenza di una query custom
                if ($ObjectTable->getQuery() != "NotExist") 
                    $array = $ObjectTable->getQuery(); // Recupero il risultato della query custom
                else $array = $this->Select($column)->Result();


                $cont=0;
                /* Se non è stato specificato quali campi mostrare allora dal risultato della query estraggo le chiavi
                   selezionate automaticamente o specificate in una query custom */
                if ($column == array("*")) 
                    /* Facendo questo controllo non vengono mostrate le colonne della tabella se non c'è almeno un risultato 
                       nella query*/
                    if (isset($array[0])) 
                        $entity = array_keys($array[0]);  // Restituisce le chiavi di un array
                    else for ($x = 0; $x < length($this->getEntityList()); $x++) { 
                            // Nel caso in cui non c'è un risultato nella query
                            $entity[$cont] = $this->getEntityList()[$x]->getName();
                            $cont++;
                        }
                else $entity = $column; 


                /* Creo i link per le azioni di delete ed edit */
                for ($i = 0; $i < length($array); $i++) {               
                    $key_entity=null;
                    for ($j=0; $j < length($array[$i]) ; $j++) { 
                        if ($j < length($key_table)) {
                            if($j==0)
                                $key_entity .= "?".$key_table[$j]."=".$array[$i][$key_table[$j]];
                            else $key_entity .= "&".$key_table[$j]."=".$array[$i][$key_table[$j]];
                        }
                    }
                    $key[$i]=$key_entity;   
                }       

           /* Verifichiamo se è stato definito un file custom per la definizione della tabella */
            if ($ObjectTable->getCustomFile())
                $uuoden= new fragmentCode("component/custom/".$ObjectTable->getCustomFile());
            else $uuoden= new fragmentCode("component/Table");

            $uuoden->injectCode("NameService",$this->getNameService());

            // Verifico se esiste un Bottone Custom
            if ($ObjectTable->getButton()) 
                $uuoden->injectCode("CustomButton",$ObjectTable->getButton());
            else $uuoden->injectCode("CustomButton",null);

            // Verifico l'esistenza di una classe custom
            // se esiste setto la variabile TD_Class con quella inserita

            if($ObjectTable->getTdClass())
                $TD_Class = $ObjectTable->getTdClass(); 
            else $TD_Class = null;
 


            //Setto il nome dela cartella del componente
            $uuoden->injectCode("module",$this->getModule());  
            /* Definiamo su quale colonna mostrare il box con le azioni di modifica 
               e cancellazione degli elementi */

            $uuoden->injectCode("td_class",$TD_Class) ;
            $uuoden->injectCode("service",$this->getNameTable()); //Setto il nome della Tabella
            $uuoden->injectCode("entity",$entity); //Setto i nomi colonna della tabella
            $uuoden->injectCode("array",$array); //Righe recuperate dal database
            $uuoden->injectCode("linkey",$key);  //Setto i link per le azioni di delete e edit
                        
            return $uuoden->get();

        } catch (Exception $e) {
            Log::Error($e);
        }

    }

}