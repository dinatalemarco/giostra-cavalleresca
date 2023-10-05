<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 */



trait FileManagement{


    /**
    * getRootDir()
    *
    * @return String
    *
    */


    private function getRootDir(){
        return realpath(dirname(__FILE__))."/../../modules/";
    }


    /**
    * CreateDir()
    *
    * @param $RotDir String
    * @param $dir String
    * @return void
    * @throws Exception Log Writes the error in the log
    *
    */



    private function CreateDir($RotDir,$dir){

        try {

            if (!is_dir($RotDir.$dir)) {
                $ArrayDir = explode('/', $dir);
                for ($i=0; $i < length($ArrayDir) ; $i++) 
                    if(!is_dir($RotDir.$ArrayDir[$i])){
                        mkdir($RotDir.$ArrayDir[$i], 0700);
                        $RotDir .= $ArrayDir[$i]."/";
                    }
                                        
            }
            
        } catch (Exception $e) {
            Log::Error($e);
        }


    }



    /**
    * Upload()
    *
    * @param $input array
    * @example $input=array(Entity=>array())
    * @return Result
    * @throws Exception Log Returns a null element and writes the error in the log
    *
    */

    public function Upload($input){


        try {

        $ListEntityFile = null;
        /* Selezioniamo tutte le entità di tipo file presenti nel servizio */
        for ($i=$cont= 0; $i < length($this->getEntityList()); $i++) {
            if ($this->getEntityList()[$i]->getType() == FILE) {
                $ListEntityFile[$cont]['entity'] = $this->getEntityList()[$i]->getName();
                $ListEntityFile[$cont]['option'] = $this->getEntityList()[$i]->getOption();
                $cont++;
            }
        }

        /* Cerco tra le entità disponibili nel servizio quella che è stata passata in input */
        for ($i=0; $i < length($ListEntityFile) ; $i++) { 

            if (isset($input[$ListEntityFile[$i]['entity']])) {

            $NewName = "";
            $NameEntity = $ListEntityFile[$i]['entity'];
            $ValueEntity = $input[$NameEntity];
            $ValueOption = $ListEntityFile[$i]['option'];


                /* Verifico che si stia caricando un file, in caso negativo il campo name risulterà vuoto */
                if ($ValueEntity['name']){ 
                    /* Verifichiamo che il file che si vuole caricare corrisponde ad un tipo
                       accettato dal sistema */
                    $ResControlFile = $this->ValidateParameter($NameEntity, $ValueEntity['name']);

                    if ($ResControlFile->getBoolean()){

                        // Determino la rot di base dei mie file   
                        $RotDir = $this->getRootDir();
                        $Module = $this->getModule()."/upload/";
                        $FileType = pathinfo($ValueEntity['name'],PATHINFO_EXTENSION);

                        // Verifico l'esistenza della cartella Upload all'interno del modulo   
                        if (!is_dir($RotDir.$Module)) 
                            mkdir($RotDir.$Module, 0700); // Non esiste, la creo

                        $RotDir .= $Module;

                        /* Verifico l'esistenza di una cartella custom */        
                        if (isset($ValueOption[CUSTOM_DIR])){ 
                            if (!is_dir($RotDir.$ValueOption[CUSTOM_DIR]))
                                $this->CreateDir($RotDir,$ValueOption[CUSTOM_DIR]);
                            $RotDir .= $ValueOption[CUSTOM_DIR]."/";
                        }
                        /* Fine Creazione Directory custom */

                        // Verifico se sono state settate impostazioni sul tipo di upload
                        if (isset($ValueOption[MAX_SIZE_FILE],$ValueOption[UNIT_OF_MEASURE])) 
                            $SIZE_FILE = $ValueOption[MAX_SIZE_FILE]*$ValueOption[UNIT_OF_MEASURE];
                        else $SIZE_FILE = 3*1000000; // Di default setto le diensioni massime a 3 MB


                        // Verifico se le dimensioni del file superano le dimensioni consentite
                        if ($ValueEntity["size"] > $SIZE_FILE) {
                            Log::Error(Language::$largeFile);
                            return new Result(false, Language::$largeFile, null);
                        }else{

                            /* Definiamo un nome random per il file caricato, formato dalla data minuti e secondi */
                            $RandomName = date("dmYhis")."_".Random_MD5(5).".".$FileType;

                            /* Prelevo il file e lo salvo con un nome Random */
                            if (move_uploaded_file($ValueEntity["tmp_name"], $RotDir.$RandomName)) {
                                return new Result(true, Language::$uploaded." ".$ValueEntity["name"] , $RandomName);
                            } else {
                                Log::Error(Language::$errorUploaded);
                                return new Result(false, Language::$errorUploaded, null);
                            }

                        }

                    }else return new Result(false, $ResControlFile->getMessage(), null);

                }else return new Result(false, "Nessun File passato in input" , " ");
                
            } 

        }


        if (length($ListEntityFile) == 0) 
            return new Result(false, "Funzione applicata ad un oggetto che non possiede Entità di tipo file", null);
        else return new Result(false, "Non si è passoto in input nessuna Entità di tipo File", null);

        

        } catch (Exception $e) {
            Log::Error($e);
            return new Result(false, $e->getMessage(), null);
        }


    }



    /**
    * DeleteFile()
    *
    * @param $input array
    * @example $input=array(Entity=>array())
    * @return Result
    * @throws Exception Log Writes the error in the log
    *
    */


    public function DeleteFile($input){

        try {
        // Determino la rot di base dei mie file   
        $RotDir = $this->getRootDir();


        $ListEntityFile = null;
        /* Selezioniamo tutte le entità di tipo file presenti nel servizio */
        for ($i=$cont= 0; $i < length($this->getEntityList()); $i++) {
            if ($this->getEntityList()[$i]->getType() == FILE) {
                $ListEntityFile[$cont]['entity'] = $this->getEntityList()[$i]->getName();
                $ListEntityFile[$cont]['option'] = $this->getEntityList()[$i]->getOption();
                $cont++;
            }
        }


            /* Scorriamo tutte le entità di tipo file presente nel servizio per cercare quella 
               passata in input, per permetterne la canellazione */
            for ($i=0; $i < length($ListEntityFile) ; $i++) { 

                /* In fase di cancellazione può capitare di dovere eliminare tutti i file legati ad N
                   entità o di cancellare un solo file lasciando gli altri file intatti, per ovviare 
                   alla cancellazione di tutti i file lagati ad un servizio, permettiamo di passare alla 
                   funzione anche il singolo nome del file che si desidera cancellare */

                if (isset($input[$ListEntityFile[$i]['entity']])){

                    $Module = $this->getModule()."/upload/";

                    $NameEntity = $ListEntityFile[$i]['entity'];
                    $ValueEntity = $input[$NameEntity];
                    $ValueOption = $ListEntityFile[$i]['option'];


                    if (is_dir($RotDir.$Module))
                        $RotDir .= $Module;
                    else new Result(false, Language::$notFoundFolder." \"".$Module);   


                    /* Verifico l'esistenza di una cartella custom */        
                    if (isset($ValueOption[CUSTOM_DIR]))
                        if (is_dir($RotDir.$ValueOption[CUSTOM_DIR])) 
                            $RotDir .= $ValueOption[CUSTOM_DIR]."/";
                        else new Result(false, Language::$notFoundFolder." \"".$ValueOption[CUSTOM_DIR]);
                        
                        // Definisco una variabile con il persorso completo con il nome del file
                        $file = $RotDir.$ValueEntity; 

                        // verifico l'esistenza del file richiesto
                        if (file_exists($file)){
                            unlink($file);
                            return new Result(true, Language::$deleteFile." ".$file); 
                        }else{
                            $Message = Language::$fileDoesNotExist." ".$file;
                            Log::Error($Message);
                            return new Result(false, $Message);        
                        } 

                }
            }

        if (length($ListEntityFile) == 0) 
            return new Result(false, "Funzione applicata ad un oggetto che non possiede Entità di tipo file",$input);
        else return new Result(false, "Non si è passoto in input nessuna Entità di tipo File",$input);        
        

        } catch (Exception $e) {
            Log::Error($e);
        }


    }





    /**
    * Download()
    *
    * @param $input array
    * @example $input=array(Entity=>'namefile')
    * @return Result
    * @throws Exception Log Writes the error in the log
    *
    */



    public function Download($input){

        try {
          
        // Determino la rot di base dei mie file   
        $RotDir = $this->getRootDir();

        $ListEntityFile = null;
        /* Selezioniamo tutte le entità di tipo file presenti nel servizio */
        for ($i=$cont= 0; $i < length($this->getEntityList()); $i++) {
            if ($this->getEntityList()[$i]->getType() == FILE) {
                $ListEntityFile[$cont]['entity'] = $this->getEntityList()[$i]->getName();
                $ListEntityFile[$cont]['option'] = $this->getEntityList()[$i]->getOption();
                $cont++;
            }
        }


            /* Scorriamo tutte le entità di tipo file presente nel servizio per cercare quella 
               passata in input, per permetterne la canellazione */
            for ($i=0; $i < length($ListEntityFile) ; $i++) {

                if (isset($input[$ListEntityFile[$i]['entity']])){

                    $Module = $this->getModule()."/upload/";

                    $NameEntity = $ListEntityFile[$i]['entity'];
                    $ValueEntity = $input[$NameEntity];
                    $ValueOption = $ListEntityFile[$i]['option'];

                    $Ext = strrchr($ValueEntity, '.');

                    if (is_dir($RotDir.$Module))
                        $RotDir .= $Module;
                    else new Result(false, Language::$notFoundFolder." \"".$Module); 


                    /* Verifico l'esistenza di una cartella custom */        
                    if (isset($ValueOption[CUSTOM_DIR]))
                        if (is_dir($RotDir.$ValueOption[CUSTOM_DIR])) 
                            $RotDir .= $ValueOption[CUSTOM_DIR]."/";
                        else new Result(false, Language::$notFoundFolder." \"".$ValueOption[CUSTOM_DIR]);
                    
                    
                    // Definisco una variabile con il persorso completo di nome del file
                    $file = $RotDir.$ValueEntity;  

                    // verifico l'esistenza del file richiesto
                    if (file_exists($file)){

                        header('Cache-control: private');
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename='.basename($file));
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        ob_clean();
                        flush();
                        readfile($file);
                        exit;

                    }else{
                        $Message = Language::$errorDownload." ".$file;
                        Log::Error($Message);
                        return new Result(false, $Message);        
                    }

                }
            } 


        } catch (Exception $e) {
            Log::Error($e);
            return new Result(false, $e->getMessage()); 
        }

    }

}