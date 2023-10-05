<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */



trait FunctionDB{

    /**
      * @var Doctrine $objDoctrine       
      */

    private $objDoctrine;

    /**
      * @var Model $uuodenObj       
      */

    private $uuodenObj;


    /**
     * FunctionDB()
     *
     * @param $objDoctrine Docreine
     * @param $uuodenObj Model
     *
     */ 

    public function FunctionDB($objDoctrine,$uuodenObj) {
        $this->objDoctrine = $objDoctrine; 
        $this->uuodenObj = $uuodenObj;
        return $this;
    }


    /**
     * getObjDoctrine()
     *
     * @return Doctrine
     *
     */ 

    private function getObjDoctrine(){
        return $this->objDoctrine;
    }



    /**
     * getUuodenObj()
     *
     * @return Model
     *
     */ 

    private function getUuodenObj(){
        return $this->uuodenObj;
    }



    /**
     * CreateTable()
     * @param $RetDocSchme String
     * @return void
     *
     */ 

    public function CreateTable($RetDocScheme=null) {

    $connect = Config::getConnectDB();
    $schema = new \Doctrine\DBAL\Schema\Schema();
    $x = $schema->createTable($this->getNameDatabaseTable());


        for ($i = 0; $i < length($this->getEntityList()); $i++) {

            $Name = $this->getEntityList()[$i]->getName();
            $Type = $this->getEntityList()[$i]->getType();
            $Option = $this->getEntityList()[$i]->getOption();

            if($Type == VARCHAR || $Type == CHAR || $Type == FILE )
                $x->addColumn($Name, "string" , $Option);

            if($Type == TEXT)
                $x->addColumn($Name, "text" , $Option);

            if($Type == INTEGER)
                $x->addColumn($Name, "integer" , $Option); 

            if($Type == FLOAT || $Type == DOUBLE )
                $x->addColumn($Name, "float" , $Option); 

            if($Type == BOOLEAN)
                $x->addColumn($Name, "boolean" , $Option);                                 

            if ($Type == DATE || $Type == DATETIME || $Type == TIME || $Type == TIMESTAMP)
                $x->addColumn($Name, "datetime" , $Option);


        }

        for ($i = 0; $i < length($this->getForeignKeyList()); $i++) {
            $x->addForeignKeyConstraint(
                $this->getForeignKeyList()[$i]->getObject()->CreateTable(true), 
                $this->getForeignKeyList()[$i]->getName(), 
                $this->getForeignKeyList()[$i]->getObject()->getKeyTable(), 
                $this->getForeignKeyList()[$i]->getOption());
        }


        // Settiamo i capi da rendere unici nel database
        if (length($this->getEntityUnique())) 
            $x->addUniqueIndex($this->getEntityUnique());
        // Settiamo la chiave della tabella
        if (length($this->getKeyTable())) 
            $x->setPrimaryKey($this->getKeyTable());


        try {

            /* Verifico se è stata chiamata la CreateTable per la creazione della tabella sul database
               o se è stata chiamata per la restituzione di un oggetto schema */
            if (!$RetDocScheme) {
                // Creazione della tabella nel database
                $platform = $connect->getDatabasePlatform();
                $queries = $schema->toSql($platform);
                 
                for ($i=0; $i < length($queries) ; $i++) { 
                    $create = $connect->prepare($queries[$i]);
                    $create->execute();
                } 

            }else return $x;    // Restituzione oggetto schema
   

        } catch (Exception $e) {
            Log::Error($e);  
        }

    }



    /**
     * Insert()
     *
     * @param $value Array[]
     * @param $session Session 
     * @param $n_form String
     * @return Result 
     * @throws Exception Log Returns a null element and writes the error in the log 
     *
     */    

    public function Insert($value,$session=null,$n_form=null) {

        try {
          
            /* ##################### Verifico se nell'oggetto c'e un tipo File ##################### */
            /* ##################################################################################### */
            $TypeFile = $this->getTypeTable(FILE);

            for ($i=0; $i < length($TypeFile) ; $i++) { 
             if (isset($value[$TypeFile[$i]]['name'])) {
                if ($value[$TypeFile[$i]]['name']) {

                    /* Verifico che siano stati inclusi i metodi per la gestione dei file */
                    if (method_exists($this, "Upload")) 
                        $ResultFile = $this->Upload(array($TypeFile[$i] => $value[$TypeFile[$i]]));
                    else return new Result(false, Language::$error_method_file);
                        
                    if ($ResultFile->getBoolean()) 
                        $value[$TypeFile[$i]] = $ResultFile->getValue();
                    else return $ResultFile; 

                }else $value[$TypeFile[$i]] = ""; 
             }  
            }
            /* ################################ Fine verifica su file ############################## */
            /* ##################################################################################### */

            $connect = Config::getConnectDB();
            $result = $this->ValidateForm($value);

            // Setto ConSub a true nel caso in cui non sia attiva la sottomissione
            $ConSub = new Result(true, Language::$submissionControl);
            /* Verifico che sia attiva la tottomissione form */
            if ($session != null && $session->GetCSRF())
                $ConSub = $session->ControlCSRF($n_form,$this->getNameTable(),'add'); 


            if ($ConSub->getBoolean()){ 
                if($result->getBoolean()){
                 // ####################################################################################
                 // Verifico l'esistenza di un tipo Time dal momento in cui devo convertirlo in DATETIME
                 /* Blocco controlli date e time, facciamo questi controlli particolari su questi
                    due tipi perchè doctrine utilizza solo il formato datetime, per ovviare a questo
                    inconveniente controlliamo se il tipo è rispettato da doctrine o dalla nostra
                    applicazione */                    
                    $TypeTime = $this->getTypeTable(TIME);
                    for ($i=0; $i < length($TypeTime) ; $i++) { 
                        $value[$TypeTime[$i]] = "0000-00-00 ".$value[$TypeTime[$i]];
                    }
                 // ####################################################################################
                 // ####################################################################################
                 $connect->insert($this->getNameDatabaseTable(), $value); 
                }
            }else return $ConSub;

            // Restituisco il risultato della validazione della form
            return $result;
           
       
        } catch (Exception $e) {
            Log::Error($e, $value); 
            return new Result(false, Language::$error);
        }

    } 



    /**
     * Delete()
     *
     * @param $value Array[]
     * @return Result 
     * @throws Exception Log Returns a null element and writes the error in the log
     *
     */

    public function Delete($value) {

        try {

            $connect = Config::getConnectDB();
            $result = $this->ValidateKey($value);

            if($result->getBoolean()){      

            /* ##################### Verifico se nell'oggetto c'e un tipo File ##################### */
            /* ##################################################################################### */

            $TypeFile = $this->getTypeTable(FILE);

            if ($TypeFile != null) {

                $condition = null;
                $customValue = null;
                $KeyTable = $this->getKeyTable();

                /* Per eliminare il file associato all'entità devo recuperare il nome del file,
                   nella variabile $value abbiamo la chiave della tabella, ricostuiamo la 
                   condizione per il recupero del nome del file */
                for ($i=0; $i < length($KeyTable); $i++) { 
                    $condition .= $KeyTable[$i]." = :".$KeyTable[$i];
                    if (length($KeyTable) > 0 && length($KeyTable)-1 < $i)
                        $condition .= " && ";
                }
                for ($i=0; $i < length($value) ; $i++) { 
                   if ($i > 0) 
                       $customValue=array_merge($customValue, array(":".$KeyTable[$i]=>$value[$KeyTable[$i]]));
                   else $customValue = array(":".$KeyTable[$i]=>$value[$KeyTable[$i]]);
                }

                $ResNameFile = $this->Select()
                                    ->Where($condition,$customValue)
                                    ->Result();

                /* Scorre tutte le entità di tipo file prelevando il nome del file che bisogna 
                   cancellare e invoco la funzione DeleteFile $i volte */
                for ($i=0; $i < length($TypeFile) ; $i++) { 
                    if (isset($ResNameFile[0][$TypeFile[$i]]) && $ResNameFile[0][$TypeFile[$i]] != null) {
                        /* Verifico che siano stati inclusi i metodi per la gestione dei file */
                        if (method_exists($this, "DeleteFile")) 
                            $this->DeleteFile(array($TypeFile[$i] => $ResNameFile[0][$TypeFile[$i]]));
                        else return new Result(false, Language::$error_method_file);

                    }
                }

                
            }

            /* ################################ Fine verifica su file ############################## */
            /* ##################################################################################### */

                // ####################################################################################
                // Verifico l'esistenza di un tipo Time dal momento in cui devo convertirlo in DATETIME
                /* Blocco controlli date e time, facciamo questi controlli particolari su questi
                   due tipi perchè doctrine utilizza solo il formato datetime, per ovviare a questo
                   inconveniente controlliamo se il tipo è rispettato da doctrine o dalla nostra
                   applicazione */             
                $TypeTime = $this->getTypeTable(TIME);
                for ($i=0; $i < length($TypeTime) ; $i++) { 
                    if (isset($value[$TypeTime[$i]])) 
                        $value[$TypeTime[$i]] = "0000-00-00 ".$value[$TypeTime[$i]];
                }
                // ####################################################################################
                // ####################################################################################

                $connect->delete($this->getNameDatabaseTable(), $value);
            }

            return $result;

        } catch (Exception $e) {
            Log::Error($e,$value); 
            return new Result(false, Language::$error);
        }
       
    } 




    /**
     * Update()
     *
     * @param $value Array[]   
     * @param $key Array[]     
     * @param $session Session    
     * @param $n_form String     
     * @return Result
     * @throws Exception Log Returns a null element and writes the error in the log
     *
     */

    public function Update($value,$key,$session=null,$n_form=null) {

        try {

        /* ##################### Verifico se nell'oggetto c'e un tipo File ##################### */
        /* ##################################################################################### */

            $TypeFile = $this->getTypeTable(FILE);

            if ($TypeFile != null) {

                $condition = null;
                $customValue = null;
                $KeyTable = $this->getKeyTable();

                /* Per poter permettere l'aggiornamento di un file ricostriamo la condizione per
                   richiamare i dati già memorizzati nel dataabse, prima di aggiornare i valori nel 
                   database verifichiamo se ad una certa entità sono statei legati altri file, cosi
                   da poter eliminare i file non più associati */
                for ($i=0; $i < length($KeyTable); $i++) { 
                    $condition .= $KeyTable[$i]." = :".$KeyTable[$i];
                    if (length($KeyTable) > 0 && length($KeyTable)-1 < $i)
                        $condition .= " && ";
                }
                for ($i=0; $i < length($key) ; $i++) { 
                   if ($i > 0) 
                     $customValue=array_merge($customValue, array(":".$KeyTable[$i]=>$key[$KeyTable[$i]]));
                   else $customValue = array(":".$KeyTable[$i]=>$key[$KeyTable[$i]]);
                }

                /* Recupero i valori precedentemente salvati */
                $ResNameFile = $this->Select($TypeFile)
                                    ->Where($condition,$customValue)
                                    ->Result();



                /* Scorro tutte le entità di tipo file, e verifico se si ha intenzione di sostituire
                   un file con uno nuovo */                                
                for ($i=0; $i < length($TypeFile) ; $i++) { 
                    /* Verifico il passaggio di un nuovo file in input */
                    if (is_array($value[$TypeFile[$i]])) {
                        /* Verifico l'esistenza di un file in input e in contemporanea l'esistenza
                           di un file precedentemente caricato cosi da poter consentire l'eliminazione
                           del file precedente */
                        if ($value[$TypeFile[$i]]['name'] && $ResNameFile[0][$TypeFile[$i]]) {

                            /* Verifico che siano stati inclusi i metodi per la gestione dei file */
                            if (method_exists($this, "DeleteFile")) 
                                $this->DeleteFile(array($TypeFile[$i] => $ResNameFile[0][$TypeFile[$i]])); 
                            else return new Result(false, Language::$error_method_file);

                        }                             
                    }                                
                }                                

                /* Lanciamo la funzione Update per tutte le entità di tipo file */
                for ($i=0; $i < length($TypeFile) ; $i++) { 
                 if (isset($value[$TypeFile[$i]]['name'])) {
                    if ($value[$TypeFile[$i]]['name']) {

                        /* Verifico che siano stati inclusi i metodi per la gestione dei file */
                        if (method_exists($this, "Upload")) 
                            $ResultFile = $this->Upload(array($TypeFile[$i] => $value[$TypeFile[$i]]));
                        else return new Result(false, Language::$error_method_file);

                        if ($ResultFile->getBoolean()) 
                            $value[$TypeFile[$i]] = $ResultFile->getValue();
                        else return $ResultFile; 

                    }else{ 
                        /* Non si sta caricando nessun nuovo file, reinseriamo il 
                           nome del file salvato in precedenza */
                        $value[$TypeFile[$i]] = $ResNameFile[0][$TypeFile[$i]];  
                    }
                 }  
                }
              
            }

            /* ################################ Fine verifica su file ############################## */
            /* ##################################################################################### */

            $connect = Config::getConnectDB();
            $resultForm = $this->ValidateForm(array_merge($value,$key));
            $resultKey = $this->ValidateKey($key);

            // Setto ConSub a tru nel caso in cui non sia attiva la sottomissione
            $ConSub = new Result(true, Language::$submissionControl);
            /* Verifico che sia attiva la tottomissione form */
            if ($session != null && $session->GetCSRF())
                $ConSub = $session->ControlCSRF($n_form,$this->getNameTable(),'edit'); 


            if($resultForm->getBoolean() && $resultKey->getBoolean()  && $ConSub->getBoolean()){

                //#####################################################################################
                // Verifico l'esistenza di un tipo Time dal momento in cui devo convertirlo in DATETIME
                /* Blocco controlli date e time, facciamo questi controlli particolari su questi
                   due tipi perchè doctrine utilizza solo il formato datetime, per ovviare a questo
                   inconveniente controlliamo se il tipo è rispettato da doctrine o dalla nostra
                   applicazione */                 
                $TypeTime = $this->getTypeTable(TIME);
                for ($i=0; $i < length($TypeTime) ; $i++) { 
                    if (isset($key[$TypeTime[$i]])) 
                        $key[$TypeTime[$i]] = "0000-00-00 ".$key[$TypeTime[$i]];
                    else $value[$TypeTime[$i]] = "0000-00-00 ".$value[$TypeTime[$i]];
                }
                // ####################################################################################
                // ####################################################################################

                $connect->update($this->getNameDatabaseTable(), $value, $key);
            }

            
            if (!$ConSub->getBoolean()) 
                return $ConSub; // Validazione form respinta
            
            if (!$resultKey->getBoolean()) 
                return $resultKey; // Validazione chiave respinta

            return $resultForm; 

        } catch (Exception $e) {
            Log::Error($e,array_merge($key,$value)); 
            return new Result(false, Language::$error);
        }

    }     



    /**
     * Select()
     *
     * @param $columsn Array[]  
     * @param $aliasTable String
     * @return Model 
     * @throws Exception Log Writes the error in the log       
     *
     */

    public function Select($columns=array("*"), $aliasTable=null) {

        try {

            $connect = Config::getConnectDB();
            $qb = $connect->createQueryBuilder();

            // Se c'è un alias significa che sto usando la select per un join
            if ($aliasTable == null) 
                $qb->select($columns)
                   ->from($this->getNameDatabaseTable());
            else $qb->select($columns)
                    ->from($this->getNameDatabaseTable(), $aliasTable);
                 
            return $this->FunctionDB($qb, $this);
              

        } catch (Exception $e) {
            Log::Error($e,$columns); 
        }

    } 


    /**
     * Where()
     *
     * @param $condition String   
     * @param $parameter Array[]
     * @return Model     
     * @throws Exception Log Writes the error in the log   
     *
     */

    public function Where($condition, $parameter) {

        try {

            $qb = $this->getObjDoctrine()
                       ->where($condition)
                       ->setParameters($parameter);        

            return $this->FunctionDB($qb, $this->getUuodenObj());
                         
        } catch (Exception $e) {
            Log::Error($e); 
        }

    }



    /**
     * AndWhere()
     *
     * @param $condition String   
     * @param $parameter Array[]
     * @return Model     
     * @throws Exception Log Writes the error in the log   
     *
     */

    public function AndWhere($condition, $parameter) {

        try {

            $qb = $this->getObjDoctrine()
                       ->andwhere($condition)
                       ->setParameters($parameter);        

            return $this->FunctionDB($qb, $this->getUuodenObj());
                         
        } catch (Exception $e) {
            Log::Error($e); 
        }

    }    




    /**
     * OrderBy()
     *
     * @param $campo String   
     * @param $metodo String
     * @return Model 
     * @throws Exception Log Writes the error in the log
     *
     */

    public function OrderBy($campo, $metodo) {

        try {

            $qb = $this->getObjDoctrine()->orderBy($campo, $metodo);

            return $this->FunctionDB($qb,$this->getUuodenObj());               
            

        } catch (Exception $e) {
            Log::Error($e); 
        }

    }



    /**
     * GroupBy()
     *
     * @param $campo String   
     * @return Model
     * @throws Exception Log Writes the error in the log
     *
     */

    public function GroupBy($campo) {

        try {

            $qb = $this->getObjDoctrine()->groupBy($campo);

            return $this->FunctionDB($qb,$this->getUuodenObj());              
            

        } catch (Exception $e) {
            Log::Error($e); 
        }

    }


    /**
     * InnerJoin()
     *
     * @param $aliasTable1 String   
     * @param $table Object
     * @param $aliasTable2 String   
     * @param $condition String        
     * @return Model
     * @throws Exception Log Writes the error in the log
     *
     */

    public function InnerJoin($aliasTable1, $table, $aliasTable2, $condition) {

        try {

            $qb = $this->getObjDoctrine()
                       ->innerJoin($aliasTable1, 
                                   $table->getNameDatabaseTable(), 
                                   $aliasTable2, 
                                   $condition);
               
            /* ############################### ATTENZIONE #################################### */
            /* Nel campo dell'oggetto Uuoden sto mettendo un solo oggetto, di conseguenza non verrà
               parsato l'oggetto table2, se si concatenano due join si perderà l'oggetto del primo join */
            return $this->FunctionDB($qb,$table);     


        } catch (Exception $e) {
            Log::Error($e); 
        }

    }



    /**
     * LeftJoin()
     *
     * @param $aliasTable1 String   
     * @param $table Object
     * @param $aliasTable2 String   
     * @param $condition String        
     * @return Model
     * @throws Exception Log Writes the error in the log
     *
     */

    public function LeftJoin($aliasTable1, $table, $aliasTable2, $condition) {

        try {

            $qb = $this->getObjDoctrine()
                       ->leftJoin($aliasTable1, 
                                   $table->getNameDatabaseTable(), 
                                   $aliasTable2, 
                                   $condition);
               
            /* ############################### ATTENZIONE #################################### */
            /* Nel campo dell'oggetto Uuoden sto mettendo un solo oggetto, di conseguenza non verrà
               parsato l'oggetto table2, se si concatenano due join si perderà l'oggetto del primo join */
            return $this->FunctionDB($qb,$table);     


        } catch (Exception $e) {
            Log::Error($e); 
        }

    }




    /**
     * MaxResult()
     *
     * @param $max int
     * @return array
     * @throws Exception Log Returns a null element and writes the error in the log
     *
     */

    public function MaxResult($max) {

        try {
                $uuodenObj = $this->getUuodenObj();

                $result = $this->getObjDoctrine()
                               ->setMaxResults($max)
                               ->execute()
                               ->fetchAll(PDO::FETCH_ASSOC);

            /* Dal momento che Doctrine mi forza ad usare un datetime, verifico se ci sono i tipi
            DATE e TIME e taglio le parti in eccesso */

            $TypeTime = $this->getUuodenObj()->getTypeTable(TIME);
            $TypeDate = $this->getUuodenObj()->getTypeTable(DATE);

            if (length($TypeTime) || length($TypeDate))
                for ($i=0; $i < length($result) ; $i++) {
                    for ($j=0; $j < length($TypeTime) ; $j++) { 
                        if (isset($result[$i][$TypeTime[$j]])) 
                        $result[$i][$TypeTime[$j]] = substr($result[$i][$TypeTime[$j]], 11);
                     } 
                    for ($j=0; $j < length($TypeDate); $j++) { 
                        if (isset($result[$i][$TypeDate[$j]])) 
                        $result[$i][$TypeDate[$j]] = substr($result[$i][$TypeDate[$j]], 0,10);
                    }                   
                }
            
            /* Fine Correzione sui tipi DATE e TIME */

                return $result;                
            

        } catch (Exception $e) {
            Log::Error($e); 
            return new Result(false, Language::$errorResult);
        }

    }





    /**
     * FirstResult()
     *
     * @param $ofset int
     * @return array
     * @throws Exception Log Returns a null element and writes the error in the log
     *
     */

    public function FirstResult($ofset) {

        try {
                $uuodenObj = $this->getUuodenObj();

                $result = $this->getObjDoctrine()
                               ->setFirstResult($ofset)
                               ->execute()
                               ->fetchAll(PDO::FETCH_ASSOC);


            /* Dal momento che Doctrine mi forza ad usare un datetime, verifico se ci sono i tipi
            DATE e TIME e taglio le parti in eccesso */

            $TypeTime = $this->getUuodenObj()->getTypeTable(TIME);
            $TypeDate = $this->getUuodenObj()->getTypeTable(DATE);

            if (length($TypeTime) || length($TypeDate))
                for ($i=0; $i < length($result) ; $i++) {
                    for ($j=0; $j < length($TypeTime) ; $j++) { 
                        if (isset($result[$i][$TypeTime[$j]])) 
                        $result[$i][$TypeTime[$j]] = substr($result[$i][$TypeTime[$j]], 11);
                     } 
                    for ($j=0; $j < length($TypeDate); $j++) { 
                        if (isset($result[$i][$TypeDate[$j]])) 
                        $result[$i][$TypeDate[$j]] = substr($result[$i][$TypeDate[$j]], 0,10);
                    }                   
                }
            
            /* Fine Correzione sui tipi DATE e TIME */


                return $result;                
            

        } catch (Exception $e) {
            Log::Error($e); 
            return new Result(false, Language::$errorResult);
        }

    }



    /**
     * Result()
     *
     * @return Array[]
     * @throws Exception Log Returns a null element and writes the error in the log
     *
     */

    public function Result() {

        try {
  
            $result = $this->getObjDoctrine()
                           ->execute()
                           ->fetchAll(PDO::FETCH_ASSOC);

            /* Dal momento che Doctrine mi forza ad usare un datetime, verifico se ci sono i tipi
            DATE e TIME e taglio le parti in eccesso */

            $TypeTime = $this->getUuodenObj()->getTypeTable(TIME);
            $TypeDate = $this->getUuodenObj()->getTypeTable(DATE);

            if (length($TypeTime) || length($TypeDate))
                for ($i=0; $i < length($result) ; $i++) {
                    for ($j=0; $j < length($TypeTime) ; $j++) { 
                        if (isset($result[$i][$TypeTime[$j]])) 
                        $result[$i][$TypeTime[$j]] = substr($result[$i][$TypeTime[$j]], 11);
                     } 
                    for ($j=0; $j < length($TypeDate); $j++) { 
                        if (isset($result[$i][$TypeDate[$j]])) 
                        $result[$i][$TypeDate[$j]] = substr($result[$i][$TypeDate[$j]], 0,10);
                    }                   
                }
            
            /* Fine Correzione sui tipi DATE e TIME */


            return $result;

        } catch (Exception $e) {
            Log::Error($e); 
            return new Result(false, Language::$errorResult);
        }

    }



}