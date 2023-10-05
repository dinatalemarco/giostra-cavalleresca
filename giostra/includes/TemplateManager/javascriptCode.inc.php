<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 */

Class javascriptCode{
	public $smarty;

	/**
	 * getOutline()
	 * @return void
     *
	*/ 

	public function __construct() {

		try {
			
	        $this->smarty = new Smarty();
			
			$this->smarty->setTemplateDir(realpath(dirname(__FILE__))."/../JSCode");
			$this->smarty->setConfigDir('TemplateManager/libs/configs');

			/* Verifico se sto utilizzando una template Admin o Public*/
			if (Settings::getFolder() == "administrator/") {
				$this->smarty->setCompileDir('../cache');
				$this->smarty->setCacheDir('../cache');		
			}else{
				// Caso in cui utilizziamo una template Public
				$this->smarty->setCompileDir('cache');
				$this->smarty->setCacheDir('cache');			
			}	
		

		} catch (Exception $e) {
			Log::Error($e); 
		}

	}
	
	/**
	 * injectCode()
	 * @param $name String
	 * @param $value String	 
	 * @return String
     *
	*/ 

	public function injectCode($name,$value){

		try {

			$this->smarty->assign($name,$value);

		} catch (Exception $e) {
			Log::Error($e);
		}
		
	}


	/**
	 * getJSCode()
	 * @return String
     *
	*/ 

	public function getJSCode(){
		
		try {

			return $this->smarty->fetch(realpath(dirname(__FILE__))."/../JSCode/jsCode.html");
		
		} catch (Exception $e) {
			Log::Error($e);
		}

	}



	/**
	 * get()
	 * @return String
     *
	*/ 

	public function get(){
		
		try {

			if (Settings::getFolder() == "administrator/") 
				$Path = "../".Path::DynamicPath(str_replace("/", "", Settings::getFolder()));
			else $Path = Path::DynamicPath(str_replace("/", "", Settings::getFolder()));
			
			$this->smarty->assign('UuodenDynamicPath',$Path);
			return $this->smarty->fetch(realpath(dirname(__FILE__))."/../JSCode/includeCode.html");
		
		} catch (Exception $e) {
			Log::Error($e);
		}

	}
}



















