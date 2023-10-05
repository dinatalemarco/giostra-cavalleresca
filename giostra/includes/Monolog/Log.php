<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Monolog
 */


// File di gestione degli errori del sistema
require_once(realpath(dirname(__FILE__)).'/vendor/autoload.php');


use Monolog\Logger;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;




class Log{
	


  /**
  * Error()
  * @param $error Exception|String 
  * @param $array Array[] 
  * @return void
  *
  */

	public static function Error($error,$array=array()){

		$Path = realpath(dirname(__FILE__)).'/../../log/ET_ErrorSystem.log';
		// Creo il file di log degli errori
		$StreamError = new StreamHandler($Path, Logger::ERROR);
		$firephp = new FirePHPHandler();

		$logger = new Logger('ET');

		// Mostro l'errore nella console del browser
		$browserHanlder = new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::ERROR);
		$streamHandler = new \Monolog\Handler\StreamHandler('php://stderr', \Monolog\Logger::ERROR);
		$logger->pushHandler($browserHanlder);
		$logger->pushHandler($streamHandler);
		
		$logger->pushHandler($StreamError);
		$logger->pushHandler($firephp);


		/* Inseriamo nel log l'utente che ha generato l'errore */
		if (isset($_SESSION['name'],$_SESSION['surname'])) 
			$user = $_SESSION['name']." ".$_SESSION['surname'];
		else $user = "Guest";


		if (is_a($error,'Exception')) 
			 $logger->addError('<user>'.$user.'</user><message>'.self::Exception($error)."</message>",$array);
		else $logger->addError('<user>'.$user.'</user><message>'.$error.'</message>',$array);		
		

		

	}

  /**
  * Warning()
  * @param $warning String 
  * @param $array Array[] 
  * @return void
  *
  */

	public static function Warning($warning,$array=array()){

		$Path = realpath(dirname(__FILE__)).'/../../log/ET_WarningSystem.log';
		// Creo il file di log degli errori
		$StreamError = new StreamHandler($Path, Logger::WARNING);
		$firephp = new FirePHPHandler();

		$logger = new Logger('ET');

		// Mostro l'errore nella console del browser
		$browserHanlder = new \Monolog\Handler\BrowserConsoleHandler(\Monolog\Logger::WARNING);
		$logger->pushHandler($browserHanlder);

		
		$logger->pushHandler($StreamError);
		$logger->pushHandler($firephp);

		$logger->addWarning($warning,$array);

	}

  /**
  * Info()
  * @param $infp String 
  * @param $array Array[] 
  * @return void
  *
  */

	public static function Info($info,$array=array()){

		$Path = realpath(dirname(__FILE__)).'/../../log/ET_InfoSystem.log';
		// Creo il file di log degli errori
		$StreamError = new StreamHandler($Path, Logger::INFO);
		$firephp = new FirePHPHandler();

		$logger = new Logger('ET');

		
		$logger->pushHandler($StreamError);
		$logger->pushHandler($firephp);

		$logger->addInfo($info,$array);
	

	}


  /**
  * Exception()
  * @param $exception Exception 
  * @return string
  *
  */

	private static function Exception($exception){

		return "[Exception] :: <b>Line:</b>".$exception->getLine().
						    " <b>File:</b> ".$exception->getFile().
						    " <b>Message:</b> ".$exception->getMessage();
		
	}



}

