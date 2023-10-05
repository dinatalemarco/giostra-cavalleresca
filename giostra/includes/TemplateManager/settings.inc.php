<?php 
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 */

const __System__ = "System";
const __Public__ = "Public";


class Settings{

	private static $Template,$Folder;
	
	/**
	 * getTemplate()
	 * @param $tem string
	 * @return void
     *
	*/ 

	public static function getTemplate(){
		return  realpath(dirname(__FILE__))."/../../".self::getFolder()."templates/".self::getOutline()."/html";
	}

	/**
	 * setFolder()
	 * @param $Folder string
	 * @return void
     *
	*/ 

	public static function setFolder($Folder){
		self::$Folder = $Folder;
	}

	/**
	 * getFolder()
	 * @return String
     *
	*/ 

	public static function getFolder(){
		return self::$Folder;
	}

	/**
	 * setOutline()
	 * @param $TemDefault String
	 * @return void
     *
	*/ 		

	public static function setOutline($TemDefault){
		self::$Template = $TemDefault;
	}
	
	/**
	 * getOutline()
	 * @return String
     *
	*/ 

	public static function getOutline(){
		return self::$Template;
	}
	

}
