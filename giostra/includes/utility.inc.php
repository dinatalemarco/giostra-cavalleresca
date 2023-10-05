<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  include
 */

    

    /**
     * length()
     *
     * @param $param Array|String|Integer
     * @return Integer   
     *
     */

    
    function length($param){

        if (is_array($param)) {
            $count = 0;
            foreach ($param as $value) $count++;
            return $count;
        }elseif (is_string($param) || is_int($param)) {
            return strlen($param);
        }elseif (is_object($param)) {
            return 0;
        }elseif ($param == null) {
            return 0;
        }

    }



    /**
     * cryptPassword()
     *
     * @param $password String 
     * @return String   
     *
     */


    function cryptPassword($password){
    	// Crea una chiave casuale
    	$random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
    	// Crea una password usando la chiave appena creata.
    	$pass = hash('sha512', $password.$random_key);

    	return array('key'=>$random_key, 'password'=>$pass);
    }


    /**
     * decryptsPassword()
     *		      
     * @param $password String         
     * @param $encryption_key String   
     * @return hash      
     *
     */

    function decryptsPassword($password, $encryption_key){

    	// codifica la password usando una chiave univoca. 
    	$pass = hash('sha512', $password.$encryption_key);

    	return $pass;
    }


    /**
     * RandomPassword()
     *            
     * @param Integer $length            
     * @return hash      
     *
     */

    function RandomPassword($length=10){

        $number = "0123456789";
        $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $lowercase = "abcdefghijklmnopqrstuvwxyz"; 

        $makepass   = '';

        mt_srand(10000000*(double)microtime());

        /* Almeno un carattere maiuscolo */
        $makepass .= $uppercase[mt_rand(0, strlen($uppercase) - 1)];
        for ($i = 0; $i < $length; $i++) {
            $makepass .= $lowercase[mt_rand(0, strlen($lowercase) - 1)];
        }
        /* Contiene almeno un numero */
        $makepass .= $number[mt_rand(0, strlen($number) - 1)];

        return $makepass;

    }


    /**
     * Random()
     *
     * @param $max_length int      
     * @return String           
     *
     */

    function Random($max_length) {

        // Stringa vuota
        $string = ""; 
        // Scelgo tutti i possibili caratteri
        $char_string = "0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz";

        for ($i=0; $i < $max_length; $i++) {

            // Estraggo un carattere dalla stringa dei possibili caratteri
            $char = substr($char_string, mt_rand(0, strlen($char_string)-1), 1);

            /* Controllo se il carattere estratto non è già stato estratto in precedenza, 
               ATTENZIONE : in questo modo le stringhe generate possono essere di lunghezze
               diverse, dal momento in cui una volta che scarto il carattere ridondande proseguo
               con il ciclo for */
            if (!strstr($string, $char)) {
                $string .= $char;              
            }              
        }            
        return $string;

    }




    /**
     * Random_MD5()
     *
     * @param $length int      
     * @return MD5           // Stringa casuale
     *
     */

    function Random_MD5($length) {
        $string = null; // Setto la stringa iniziale come nulla
        // genera una stringa casuale che ha lunghezza length
        for ($i = 0; $i <= ($length/32); $i++)
            $string .= md5(time()+rand(0,99));

        // indice di partenza limite
        $max_point = (32*$i)-$length;

        // seleziona la stringa, utilizzando come indice iniziale
        // un valore tra 0 e $max_point
        $random = substr($string, rand(0, $max_point), $length);

        return $random;
    }


    /**
    * DirSize()
    *
    * @param $directory String
    * @return Integer
    * @throws Exception Log Returns a null element and writes the error in the log
    *
    */


    function DirSize($directory) {

        $size=0;

        try {
            foreach(glob($directory.'/*') as $file)
                if(is_file($file))
                    $size+=filesize($file);
                else $size+=FileSystem::DirSize($file);

            return $size;   
        } catch (Exception $e) {
            Log::Error($e->getMessage());
            return 0;       
        }

    }

    /**
    * ReadingDirectory()
    *
    * @param $directory String
    * @param $filter String
    * @return Array
    * @throws Exception Log Returns a null element and writes the error in the log
    *
    */



    function ReadingDirectory($directory, $filter = '') {

        $list = array();
        $dir = dir($directory);

        try {

            if($directory{strlen($directory)-1} !== '/' && $directory{strlen($directory)-1} !== '\\') 
                $directory .= '/';

            if ($dir) 
                while($entry=$dir->read()) {

                    $path_assoluto = $directory.$entry;

                    if(is_dir($path_assoluto) && $entry != "." && $entry != '..') 
                        $list = array_merge($list, FileSystem::ReadingDirectory($path_assoluto, $filter));
                    else $add = true;

                        if($filter) {
                            $explode = explode('.', $path_assoluto);
                            $ext = array_pop($explode);
                            if(strpos(strtolower($path_assoluto), $filter) === false) 
                                $add = false;
                        }

                        if($add) $list[] = $path_assoluto;
                }
            
            $dir->close();

            return $list;   
        } catch (Exception $e) {
            Log::Error($e);
            return array();         
        }



    }


    /**
    * ListFileDirectory()
    *
    * @param $directory String
    * @return Array[]
    * @throws Exception Log Returns a null element and writes the error in the log
    *
    */



    function ListFileDirectory($directory) {

        $List=null;

        try {

            // Open a known directory, and proceed to read its contents
            if (is_dir($directory)) {
                if ($handle = opendir($directory)) {
                    $cont=0;
                    while (($file = readdir($handle)) !== false) 
                        if ($file!='.' && $file!='..'){ 
                            $List[$cont] = $file;
                            $cont++;
                        }
                    
                    closedir($handle);
                }
            }

            return $List;   
        } catch (Exception $e) {
            Log::Error($e);
            return array();             
        }


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



    function CreateDir($RotDir,$dir){

        try {

            if (!is_dir($RotDir.$dir)) {
                $ArrayDir = explode('/', $dir);
                for ($i=0; $i < count($ArrayDir) ; $i++) 
                    if(!is_dir($RotDir.$ArrayDir[$i])){
                        mkdir($RotDir.$ArrayDir[$i], 0700);
                        $RotDir .= $ArrayDir[$i]."/";
                    }
                                        
            }
            
        } catch (Exception $e) {
            Log::Error($e);
        }


    }




