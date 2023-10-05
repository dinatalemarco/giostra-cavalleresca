<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  includes
 */


class Config {

    /**
      * @var String $nameSite       
      */

    private static  $nameSite = "Uuoden PHP Web Framework";

    /**
      * @var String $basePath       
      */

    private static  $basePath = "/";

    /**
      * @var String $language       
      */

    private static  $language = "it";

    /**
      * @var String $prefix       
      */

    private static  $prefix;

    /**
      * @var String $temSystem       
      */

    private static  $temSystem;

    /**
      * @var String $temPublic       
      */

    private static  $temPublic;


    /**
      * @var SmtpMail $account_mail       
      */

    private static  $account_mail;

    /**
      * @var Doctrine $connect_db       
      */

    private static  $connect_db;


	/**
	* SetNameSite()
	*		
	* @param $basePath string      
	* @return void      
	*
	*/

	public static function SetNameSite($name){
		self::$nameSite = $name;
	}


	/**
	* SetRootSite()
	*		
	* @param $basePath string      
	* @return void      
	*
	*/

	public static function SetRootSite($basePath){
		self::$basePath = $basePath;
	}


	/**
	* SetLanguage()
	*
	* @param $language string		      
	* @return void      
	*
	*/

	public static function SetLanguage($language){
		self::$language = $language;
	}

	/**
	* SetPrefixTable()
	*	
	* @param $prefix string	        
	* @return void      
	*
	*/

	public static function SetPrefixTable($prefix){
		self::$prefix = $prefix;
	}

	/**
	* SetTemplateSystem()
	*
	* @param $template string		      
	* @return void      
	*
	*/

	public static function SetTemplateSystem($template){
		self::$temSystem = $template;
	}

	/**
	* SetTemplatePublic()
	*
	* @param $template string		      
	* @return void      
	*
	*/

	public static function SetTemplatePublic($template){
		self::$temPublic = $template;
	}


	/**
	* SetAccountMail()
	*	
	* @param $server String
	* @param $port Integer
	* @param $security String
	* @param $autentication_smtp Boolean    
	* @param $email String
	* @param $password String  
	* @return void      
	*
	*/

	public static function SetAccountMail($server,$port,$security,$autentication_smtp,$email,$password){
		
		$SendMail = new SmtpMail();
		$SendMail->SetLanguage(self::getLanguage());
		$SendMail->SetFromName(self::getNameSite());
		$SendMail->SetServer($server,$port,$security,$autentication_smtp);
		$SendMail->SetAccount($email,$password);	

		self::$account_mail = $SendMail;

	}


	/**
	* SetConnectDB()
	*
	* @param $dbname String
	* @param $user String
	* @param $password String
	* @param $host String    
	* @param $driver String			        
	* @return void      
	*
	*/

	public static function SetConnectDB($dbname,$user,$password,$host,$driver){

		$config = new \Doctrine\DBAL\Configuration();

		$Params = array('dbname'   => $dbname,
						'user'     => $user,
						'password' => $password,
						'host'     => $host,
						'driver'   => $driver);
		
		self::$connect_db = \Doctrine\DBAL\DriverManager::getConnection($Params,$config);

	}


	/**
	* getNameSite()
	*		    
	* @return string      
	*
	*/

	public static function getNameSite(){
		return self::$nameSite;
	}


	/**
	* getRootSite()
	*		    
	* @return string      
	*
	*/

	public static function getRootSite(){
		return self::$basePath;
	}


	/**
	* getLanguage()
	*		      
	* @return string      
	*
	*/

	public static function getLanguage(){
		return self::$language;
	}

	/**
	* getPrefixTable()
	*	      
	* @return string      
	*
	*/

	public static function getPrefixTable(){
		return self::$prefix;
	}

	/**
	* getTemplateSystem()
	*		      
	* @return string      
	*
	*/

	public static function getTemplateSystem(){
		return self::$temSystem;
	}

	/**
	* getTemplatePublic()
	*		      
	* @return string      
	*
	*/

	public static function getTemplatePublic(){
		return self::$temPublic;
	}

	/**
	* getAccountMail()
	*	 
	* @return void      
	*
	*/

	public static function getAccountMail(){
		return self::$account_mail;
	}


	/**
	* getConnectDB()
	*
	* @param $dbname String
	* @param $user String
	* @param $password String
	* @param $host String    
	* @param $driver String			        
	* @return void      
	*
	*/

	public static function getConnectDB(){
		return self::$connect_db;
	}



}
