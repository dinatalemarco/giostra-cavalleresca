<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 */

Class OpenOutLine  {

	var $name,$smarty,$file;


	/**
	 * getOutline()
	 * @param $folder String
	 * @return String
     *
	*/ 

    public function __construct($folder) {

    	try {	

		$this->smarty = new Smarty();
		
		$this->smarty->setTemplateDir(Settings::getTemplate());
		$this->smarty->setConfigDir('TemplateManager/libs/configs');


		if ($folder == __Public__) {
			Settings::setFolder("");
			Settings::setOutline(Config::getTemplatePublic());
				$this->smarty->setCompileDir('cache');
				$this->smarty->setCacheDir('cache');			
		}else if ($folder == __System__){
			Settings::setFolder("administrator/");
			Settings::setOutline(Config::getTemplateSystem());	
				$this->smarty->setCompileDir('../cache');
				$this->smarty->setCacheDir('../cache');					
		}


		$this->name = Settings::getTemplate();
		$this->setOutline();
    		
    	} catch (Exception $e) {
    		Log::Error($e); 
    	}

	}
 

	/**
	 * setOutline()
	 * @param $outline String
	 * @return String
     *
	*/ 

	public function setOutline($outline="") {

		try {
			
			if ($outline=="")
				 $this->file = Settings::getTemplate()."/outline.html"; 
			else if (!strpos($outline, "."))
					  $this->file = Settings::getTemplate()."/".$outline.".html";  
				 else $this->file = Settings::getTemplate()."/".$outline;

		} catch (Exception $e) {
			Log::Error($e);
		}

	}


	/**
	 * closeOutline()
	 * @return void
     *
	*/ 

	public function closeOutline(){

		try {

			$this->smarty->display($this->file);

		} catch (Exception $e) {
			Log::Error($e);
		}

	}
	
	/**
	 * injectCode()
	 * @param $name String
	 * @param $value String
	 * @return void
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
			$this->smarty->assign('UuodenRootSite',Config::getRootSite()."/administrator");
		else $this->smarty->assign('UuodenRootSite',Config::getRootSite());

	}



	/** 
	 * @return String
     *
	*/ 

	public function get(){
		
		try {

			$this->globalPlaceholders();
			return $this->smarty->fetch("{$this->file}");

		} catch (Exception $e) {
			Log::Error($e);
		}

	}
	

}
