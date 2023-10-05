<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Security
 */

class Session{


  /**
    * @var String $Name       
    */

  private $Name = "session_uuoden";

    /**
    * @var int $Duration       
    */

  private $Duration = 1;

    /**
    * @var Boolean $Access       
    */

  private $Access = false;

    /**
    * @var Boolean $Https       
    */

  private $Https = false;

    /**
    * @var int $Downtime       
    */

  private $Downtime = 5;

    /**
    * @var int $LoginAttempts       
    */

  private $LoginAttempts = 4;

    /**
    * @var Boolean $OnlySubmission       
    */

  private $OnlySubmission = false;

    /**
    * @var int $DurationSubmission       
    */

  private $DurationSubmission = 2;




  /**
  * SetName()
  * @param $name String 
  * @return void
  *
  */ 

  public function SetName($name){
     $this->Name = $name;
  }


  /**
  * SetDuration()
  * @param $duration int 
  * @return void
  *
  */ 

  public function SetDuration($duration){
     $this->Duration = $duration;
  }


  /**
  * SetNoAccessJs()
  * @param $access bool
  * @return void
  *
  */ 

  public function SetNoAccessJs($access){
     $this->Access = $access;
  }


  /**
  * SetHttps()
  * @param $https bool
  * @return void
  *
  */ 

  public function SetHttps($https){
     $this->Https = $https;
  }


  /**
  * SetDowntime()
  * @param $Downtime int 
  * @return void
  *
  */ 

  public function SetDowntime($Downtime){
     $this->Downtime = $Downtime;
  }


  /**
  * SetLoginAttempts()
  * @param $number int 
  * @return void
  *
  */

  public function SetLoginAttempts($number){
      $this->LoginAttempts = $number;
  }


  /**
  * SetCSRF()
  * @param $boolean Bool 
  * @return void
  *
  */

  public function SetCSRF($boolean){
      $this->OnlySubmission = $boolean;
  }


  /**
  * DurationSubmission()
  * @param $time int 
  * @return void
  *
  */

  public function SetDurationSubmission($time){
      $this->DurationSubmission = $time;
  }

  /**
  * GetName()
  * @return String
  *
  */ 

  public function GetName(){
     return $this->Name;
  }


  /**
  * GetDuration()
  * @return int
  *
  */ 

  public function GetDuration(){
     return $this->Duration;
  }


  /**
  * GetNoAccessJs()
  * @return bool
  *
  */ 

  public function GetNoAccessJs(){
     return $this->Access;
  }


  /**
  * GetHttps()
  * @return bool
  *
  */ 

  public function GetHttps(){
     return $this->Https;
  }


  /**
  * GetDowntime()
  * @return int
  *
  */ 

  public function GetDowntime(){
     return $this->Downtime;
  }


  /**
  * GetLoginAttempts()
  * @return int
  *
  */

  public function GetLoginAttempts(){
      return $this->LoginAttempts;
  }


  /**
  * GetCSRF()
  * @return bool
  *
  */

  public function GetCSRF(){
      return $this->OnlySubmission;
  }


  /**
  * GetDurationSubmission()
  * @return int
  *
  */

  public function GetDurationSubmission(){
      return $this->DurationSubmission;
  }



  /**
  * Start()
  * @return void
  *
  */ 

  public function Start() {

    $Name = $this->GetName();
    $Duration = $this->GetDuration()*60*60;
    $Https = $this->GetHttps();
    $JsAccess = $this->GetNoAccessJs(); /* Rende il cookie invisibile a JavaScript e altri linguaggi client-side presenti nella pagina*/

    session_name($Name);      // Imposta il nome di sessione
    ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
    ini_set('session.cookie_lifetime', $Duration);
    ini_set('session.gc_maxlifetime', $Duration); 
    ini_set('session.hash_function', 1); //impone la codifica con l’algoritmo SHA1 che produce stringhe da 32 caratteri
    $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
    session_set_cookie_params($cookieParams["lifetime"], 
                              $cookieParams["path"], 
                              $cookieParams["domain"], 
                              $Https, 
                              $JsAccess); 
    session_start();          // Avvia la sessione php.
    session_regenerate_id();  // Rigenera la sessione e cancella quella creata in precedenza.

  }


  /**
  * Register()
  * @param $param array[] 
  * @param $GroupName string
  * @return Result
  *
  */ 

  public function Register($param,$GroupName=null){

    try {



    } catch (Exception $e) {
      Log::Error($e);
      return new Result(false, Language::$error);
    }

  }


  /**
  * StartResetPassword()
  * 
  * @param $email String
  * @return Result
  *
  */ 


  public function StartResetPassword($email){


    try{




    }catch(Exception $e){ //to handle error
       Log::Error($e);
    } 


  }  



  /**
  * ResetPassword()
  * 
  * @param $id int
  * @param $codeMail String  
  * @return Result
  *
  */ 


  public function ResetPassword($id, $codeMail){


    try{




    }catch(Exception $e){ //to handle error
      Log::Error($e);
    } 


  }  


  /**
  * Login()
  * @param $email String
  * @param $password String
  * @return bool
  *
  */ 

  public function Login($email, $password) {

  try {
      
      $Users = new Users();

      if(Validate::INPUT('EMAIL',$email) && Validate::INPUT('PASSWORD',$password)){ 


           $stmt = $Users->Select(array('id','name','surname','password','encryption_key'))
                         ->Where('email = :email',array(':email' => $email))
                         ->Result();


            if(length($stmt) == 1) { // se l'utente esiste
                $user_id = $stmt[0]['id'];
                $name = $stmt[0]['name'];
                $surname = $stmt[0]['surname'];
                $db_password = $stmt[0]['password'];
                $encryption_key = $stmt[0]['encryption_key'];

                // codifica la password usando una chiave univoca. 
                $password = decryptsPassword($password, $encryption_key);
       
               /* verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi
                  tentativi di accesso errati.*/
               if($this->BruteForceAttack($user_id)) { 
                  // Account disabilitato
                  /* Invia un e-mail all'utente avvisandolo che il suo account è stato
                     disabilitato. */

                  Log::Info(Language::$disabledAccount, array('email'=>$email));

                  $SendMail = Config::getAccountMail();;
                  $SendMail->AddAddress(array($email));
                  $SendMail->AddMessage(Language::$disabledAccount,
                                        Language::$messageDisabledAccount);
                  $SendMail->Send();

                  return false;
               } else {
                   /* Verifica che la password memorizzata nel database corrisponda alla
                      password fornita dall'utente. */
                   if($db_password == $password) { 
                      // Password corretta!            
                         // Recupero il parametro 'user-agent' relativo all'utente corrente.
                         $user_browser = $_SERVER['HTTP_USER_AGENT']; 
                          
                         // ci proteggiamo da un attacco XSS
                         $ControlStringAttackXSS = "/[^a-zA-Z0-9_\-]+/";
                         $ControlNumberAttackXSS = "/[^0-9]+/";
                         $user_id = preg_replace($ControlNumberAttackXSS, "", $user_id); 
                         $name = preg_replace($ControlStringAttackXSS, "", $name);
                         $surname = preg_replace($ControlStringAttackXSS, "", $surname); 

                         $_SESSION['timeout'] = time(); 
                         $_SESSION['user_id'] = $user_id; 
                         $_SESSION['name'] = $name;
                         $_SESSION['surname'] = $surname;
                         $_SESSION['email'] = $email;
                         $_SESSION['login_string'] = hash('sha512', $password.$user_browser);                     

                        // Rimuovo tutti i tentativi di connessione falliti
                          try{
                            $Attempts = new Attempts();
                            $Attempts->Delete(array('user_id' => $user_id));
                          }catch(PDOException $e){ //to handle error
                            Log::Error($e->getMessage());
                          }   

                         return true;   // Login eseguito correttamente

                   } else {
                      // Password errata
                      // Registriamo il tentativo fallito nel database.                  
                      $this->saveAccessFailed($user_id);
                      return false;  // Login Fallito
                   }
               }
            } else {
              // L'utente inserito non esiste.
              Log::Info(Language::$errorLogin, array('email'=>$email));
              return false;
            } 
            // Fine controllo esistenza utente
      }else{
          Log::Info(Language::$errorLogin, array('email'=>$email));
          return false;
      }
      //Fine controllo Email e Password


    } catch (Exception $e) {
        Log::Error($e);
        return false; // In seguito ad un errore che potrebbe compromettere l'esito dei controlli
                      // viene negato l'accesso
    } 
        

  }



  /**
  * saveAccessFailed()
  * @param $user_id int 
  * @return void
  *
  */ 

  private function saveAccessFailed($user_id){

    try{
        $Attempts = new Attempts();          
        /* Prelevo i tentativi passati */
        $TentP = $Attempts->Select(array('count'))
                          ->Where('user_id = :id',array(':id' => $user_id))
                          ->Result();

            if (length($TentP) == 0) {
                /* Non ci sono tentatipi di accesso errati */
                $Attempts->Insert(array('user_id' => $user_id, 
                                        'time' => date("H:i:s"), 
                                        'date' => date("Y-m-d"), 
                                        'ip' => getIpAddress(),
                                        'count' => 1 ));
            }else{
                /* Ci sono tentativi di accesso errati, incremento il contatore dei tentativi */
                $Attempts->Update(array('time' => date("H:i:s"), 
                                        'date' => date("Y-m-d"), 
                                        'ip' => getIpAddress(),
                                        'count' => $TentP[0]['count']+1), 
                                        array('user_id' => $user_id));

            }
                                                    
      }catch(PDOException $e){ //to handle error
          Log::Error($e->getMessage());
      }


  }


  /**
  * getPermission()
  * @return array array('Modules' => array(), 'Service' => array())
  *
  */ 

  public function getPermission(){


    try {
    
      if ($this->LoginControl()){
         /* Verifico se sono stati già inseriti in sessione i permessi sui moduli e sui servizi */
         if (!isset($_SESSION['PermissionModules'],$_SESSION['PermissionService'])) {
            $Users = new Users();
            $ObjModules = new Modules();
                    
            $id_group = $Users->Select(array('_group'))
                              ->Where('id = :id', 
                                array(':id' => $_SESSION['user_id']))
                              ->Result();                   
                                                    
            // Recupero i componenti a cui ha accesso l'utente                      
            $Modules = $ObjModules->Select(array('name'), 'm')
                                  ->InnerJoin('m', new Permissions(),'gm','m.id = gm.id_module')
                                  ->Where('gm.id_group = :id_group && gm._reading = :_reading',
                                      array(':_reading' => 1, 
                                            ':id_group' => $id_group[0]['_group']))
                                  ->GroupBy('id_module')
                                  ->Result(); 


            // Recupero i permessi su un certo servizio                    
            $Service = $ObjModules->Select(array('name','service','_reading','_insert','_update','_delete'),'m')
                                  ->InnerJoin('m', new Permissions(), 'gm','m.id = gm.id_module')
                                  ->Where('gm.id_group = :id_group && gm._reading = :_reading',
                                           array(':_reading' => 1,
                                          ':id_group' => $id_group[0]['_group']))                   
                                  ->Result(); 


            // Recupero le azioni custom su un certo servizio                   
            $Extend_Permissions = $ObjModules->Select(array('name','service','action','permission'),'m')
                                     ->InnerJoin('m', new Extend_Permissions(), 'gm','m.id = gm.id_module')
                                     ->Where('gm.id_group = :id_group',
                                       array(':id_group' => $id_group[0]['_group']))                   
                                     ->Result(); 



            $_SESSION['PermissionModules'] = $Modules;
            $_SESSION['PermissionService'] = $Service;
            $_SESSION['ExtendPermission']  = $Extend_Permissions;

            return array('Modules' => $Modules, 'Service' =>  $Service, 'ExtendPermission' => $Extend_Permissions);

         }else return array('Modules' => $_SESSION['PermissionModules'], 
                            'Service' => $_SESSION['PermissionService'],
                            'ExtendPermission' => $_SESSION['ExtendPermission']);

      }else return array('Modules' => null, 'Service' =>  null, 'ExtendPermission' =>  null);

    } catch (Exception $e) {
        Log::Error($e);
        return array('Modules' => null, 'Service' =>  null, 'ExtendPermission' =>  null);
    }


  }



  /**
  * CheckPermission()
  * @return boolean
  *
  */ 

  public function CheckPermission($mod,$serv,$act){

    try {
      
      if ($this->LoginControl()) {

        $Permission = $this->getPermission();
        $control=false;
        /* Azzeriamo i permessi in precedenza caricati */
        $_SESSION['module'] = $_SESSION['service'] = null;
        $_SESSION['reading'] = $_SESSION['insert'] = $_SESSION['update'] = $_SESSION['delete'] = null;
     
        for ($i=0; $i < length($Permission['Modules']) ; $i++){ 
          // Verifico se ho i permessi per accedere al modulo che voglio visualizzare 
          if ($mod == $Permission['Modules'][$i]['name']){ 
                   // Se il servizio è già stato settato evito di settare nuovamente i permessi
                   //if ($_SESSION['service'] != $serv) 
            for ($j=0; $j < length($Permission['Service']) ; $j++){
              if ($mod  == $Permission['Service'][$j]['name'] && $serv == $Permission['Service'][$j]['service']){
              /* Scrivo in sessione i permessi definiti sul servizio in uso */
                $_SESSION['module']  = $Permission['Service'][$j]['name'];  
                $_SESSION['service'] = $Permission['Service'][$j]['service']; 
                $_SESSION['reading'] = $Permission['Service'][$j]['_reading']; 
                $_SESSION['insert']  = $Permission['Service'][$j]['_insert'];  
                $_SESSION['update']  = $Permission['Service'][$j]['_update'];  
                $_SESSION['delete']  = $Permission['Service'][$j]['_delete'];  
              }
            }


            $_SESSION['c_action'] = null;
            /* Inseriamo nella variabile di sessione c_action le azione custom definite
               sul servizio in uso */
            for ($j=$cont=0; $j < length($Permission['ExtendPermission']) ; $j++) { 
              if ( $mod  == $Permission['ExtendPermission'][$j]['name'] && 
                $serv == $Permission['ExtendPermission'][$j]['service']){
                $_SESSION['c_action'][$cont]['action'] = $Permission['ExtendPermission'][$j]['action'];
                $_SESSION['c_action'][$cont]['permission'] = $Permission['ExtendPermission'][$j]['permission'];
                $cont++;
              }
            }

          }
        }

        // Verifico se i permessi in memoria sono dello stesso servizio        
        if (($_SESSION['module'] == $mod)&&($_SESSION['service'] == $serv)) {

          if ($act == 'view' && $_SESSION['reading']) 
            $control=true;

          if (($act == 'insert' || $act == 'add') && $_SESSION['insert'])      
            $control=true;

          if (($act == 'update' || $act == 'edit') && $_SESSION['update'])      
            $control=true;
     
          if ( $act == 'delete'  && $_SESSION['delete'] )      
            $control=true; 


          /* Se l'azione non ha prodotto un esito positivo allora verifichiamo che l'utente
             non voglia fare accesso ad un azione definita in quelle cutom, facciamo && con i
             permissi di lettura in quanto è obbligatorio avere i permessi di lettura su un 
             modulo per poter eseguire qualsiasi azione */
            for ($i=0; $i < length($_SESSION['c_action']) && !$control ; $i++) { 
              if ($_SESSION['c_action'][$i]['action'] == $act ) 
                $control = ($_SESSION['reading'] && $_SESSION['c_action'][$i]['permission']);    
            }


        }

        return $control;

      }else return false;

    } catch (Exception $e) {
        Log::Error($e);
        return false;
    }  


  }



  /**
  * LogOut()
  * @return void
  *
  */ 

  public function LogOut(){

    try {
      // Elimino le Form non sottomesse legate ad un utente
      $Sub = new Submission();
      $User = $_SESSION['user_id'];
      $Sub->Delete(array('user' => $User));


        // Elimina tutti i valori della sessione.
        $_SESSION = array();
        // Recupera i parametri di sessione.
        $params = session_get_cookie_params();
        // Cancella i cookie attuali.
        setcookie(session_name(),'',
                  time() - 42000, 
                  $params["path"], 
                  $params["domain"], 
                  $params["secure"], 
                  $params["httponly"]);
        // Cancella la sessione.
        session_destroy();

      } catch (Exception $e) {
        Log::Error($e);  
    }


  }



  /**
  * BruteForceAttack()
  * @param $user_id int 
  * @return bool
  *
  */ 

  public function BruteForceAttack($user_id) {

    $Attempts = new Attempts();
    // Recuupero il numero massimo di tentativi di login
    $LoginAttempts = $this->GetLoginAttempts();

        // Vengono analizzati tutti i tentativi di login.
        try{

            $stmt = $Attempts->Select(array('count'))
                             ->Where('user_id = :user_id', array(':user_id' => $user_id))
                             ->Result();
            
            // Verifico l'esistenza di più di 8 tentativi di login falliti.
            if (isset($stmt[0]['count']))            
              if($stmt[0]['count'] >= $LoginAttempts) 
                 return true;     // Numero di tentativi massimi superato
               else return false; // Non è stato superato il numero di tentativi massimi 
            else return false;    // Non è stato superato il numero di tentativi massimi 
              
                                  
        }catch(Exception $e){ //to handle error
            Log::Error($e);
            return true;
        }
     
  }



  /**
  * LoginControl()
  * @return bool
  *
  */ 

  public function LoginControl() {

    try {
      
       $Users = new Users();

       // Verifica che tutte le variabili di sessione siano impostate correttamente
       if(isset($_SESSION['user_id'], $_SESSION['name'], $_SESSION['surname'], $_SESSION['login_string'])) {
         $user_id = $_SESSION['user_id'];
         $login_string = $_SESSION['login_string'];
         $name = $_SESSION['name'];     
         $name = $_SESSION['surname'];   
         $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.


         if (Validate::INPUT('INTEGER',$user_id)) {
            try{
                  $stmt = $Users->Select(array('password'))
                                ->Where('id = :id', array(':id' => $user_id))
                                ->Result();
                  $exists = length($stmt);           
                                      
            }catch(PDOException $e){ //to handle error
                Log::Error($e->getMessage());
            }
         }else{
              return false;
         }

            if($exists == 1) { // se l'utente esiste
               $password=$stmt[0]['password'];
               $login_check = decryptsPassword($password, $user_browser); 
               if($login_check == $login_string) 
                  return true;  // Login eseguito!!!!
                else return false; //  Login non eseguito
               
            } else return false; // Login non eseguito
            

       } else {
         // Login non eseguito
         return false;
       }


    } catch (Exception $e) {
      Log::Error($e);
      return false;       
    }

  }


  /**
  * LockApplication()
  * @return bool
  *
  */ 

  public function LockApplication() {

    try {
      
        $inactive = $this->GetDowntime()*60;  // Tempo massimo di inattività

        if(isset($_SESSION['timeout']) ) {
          $elapsedTime = time() - $_SESSION['timeout']; // Tempo trascorso

          if($elapsedTime > $inactive)
             return true; // Tempo trascoso maggiore del tempo massimo
        }

        $_SESSION['timeout'] = time();   // Setto nella sessione l'ora dell'ultima attività registrata

        return false;

    } catch (Exception $e) {
      Log::Error($e);
      return true;        
    }

  }


  /**
  * UnlockApplication()
  * @param $pass_lock String
  * @return bool
  *
  */ 

  public function UnlockApplication($pass_lock) {

  try {
        // Recupero la password e la chiave di cifratura della password
        $Users = new Users();
        $infoUser = $Users->Select(array('encryption_key','password'))
                          ->Where('id = :id',array(':id' => $_SESSION['user_id']))
                          ->Result();
        // Cripto la chiave immessa dall'utente
        $password = decryptsPassword($pass_lock, $infoUser[0]['encryption_key']);

        // Verifico la concidenza delle chiavi 
        if($password == $infoUser[0]['password']){
          // verificato lo sblocco della pagina setto im timer con il nuovo orario
            $_SESSION['timeout'] = time();  
            return true; /* Sblocco eseguito con successo */
        }else{ return false; }  /* Errore nell'inserimento password */

    } catch (Exception $e) {
      Log::Error($e);
      return false;
    }

  }  



  /**
  * NewFormCSRF()
  *
  * @param $random String
  * @param $table String
  * @param $action String
  * @return void
  *
  */ 


  public function NewFormCSRF($random,$table,$action){

    try {
      $Submission = new Submission();
      $User = $_SESSION['user_id'];
      $Ip = getIpAddress();

        $Submission->Insert(array('random_sub' => $random,
                                  'table_sub'  => $table, 
                                  'action'     => $action,
                                  'time'       => time(),  
                                  'ip'         => $Ip,
                                  'user'       => $User)); 

    } catch (Exception $e) {
      Log::Error($e);
    }

  }



  /**
  * ControlCSRF()
  *
  * @param $random String
  * @param $table String
  * @param $action String  
  * @return void
  *
  */ 


  public function ControlCSRF($random,$table,$action){

    try {

        $MaxTime = 60 * $this->GetDurationSubmission();
        $Sub = new Submission();
        $User = $_SESSION['user_id'];
        $Ip = getIpAddress();

        // Setto la risposta nel caso non coincidono (table_sub,action,user,ip)
        $message = Language::$controlSubmission;
        $Control = new Result(false,$message);
        $MessageLog = null;

            $Result = $Sub->Select(array('random_sub','table_sub','action','time','ip','user'))
                          ->Where('random_sub = :random_sub', array(':random_sub'=> $random))
                          ->Result();   

            if (length($Result) != 0) {
              $elapsedTime = time() - $Result[0]['time']; // Tempo trascorso
              if ($elapsedTime < $MaxTime){ 
                if ($Result[0]['table_sub'] == $table) 
                  if ($Result[0]['action'] == $action) 
                    if ($Result[0]['user'] == $User) 
                      if ($Result[0]['ip'] == $Ip) 
                        $Control = new Result(true, Language::$succesSubmission);
                }else $MessageLog = Language::$timeSubmissionExpired;

                $Sub->Delete(array('random_sub' => $random));
            }else $MessageLog = Language::$errorAuthorizationSubmission;

        if ($MessageLog){
          Log::Error($MessageLog);
          $Control = new Result(false,$MessageLog);
        }

        return $Control;


    } catch (Exception $e) {
        Log::Error($e);
        return Result(false,$e);
    }

  }


}


  /**
  * getIpAddress()
  *
  * @return String
  *
  */ 


function getIpAddress() {

  if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip=$_SERVER['HTTP_CLIENT_IP']; // share internet
  }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; // pass from proxy
    }else{
      $ip=$_SERVER['REMOTE_ADDR']; //Restituisce l'ip del client connesso
      }

  return $ip;

}



