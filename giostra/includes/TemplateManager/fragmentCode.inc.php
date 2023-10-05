<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 */

Class fragmentCode{
	public $smarty, $Emti_Template;


	/**
	 * injectCode()
	 * @param $file String 
	 * @return void
     *
	*/ 

	public function __construct($file) {

		try {

			if (!strpos($file, ".")) 
				$this->Emti_Template=$file.".html";
			else $this->Emti_Template=$file;
			
	        $this->smarty = new Smarty();
			
			$this->smarty->setTemplateDir(Settings::getTemplate());
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
	 * @return void
     *
	*/ 

	public function globalPlaceholders(){

		$Path = Path::DynamicPath(str_replace("/", "", Settings::getFolder()));
		$this->smarty->assign('UuodenDynamicPath',$Path);

		if (Settings::getFolder() == "administrator/") 
			$this->smarty->assign('UuodenRootSite', Config::getRootSite()."/administrator");
		else $this->smarty->assign('UuodenRootSite', Config::getRootSite());

	}



	/** 
	 * @return String
     *
	*/ 

	public function get(){
		
		try {

			$this->globalPlaceholders();
			return $this->smarty->fetch(Settings::getTemplate()."/{$this->Emti_Template}");

		} catch (Exception $e) {
			Log::Error($e);
		}

	}
}
