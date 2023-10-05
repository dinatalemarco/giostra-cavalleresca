<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 */

class Path{


	/**
	 * DynamicPath()
	 * @param $base array
	 * @return void
     *
	*/ 

	public static function DynamicPath($base){

		$url = explode("/", $_SERVER['REQUEST_URI']);
		$guard = false;
		$pars = $result = null;


		if ($base == null) 
			$base = substr(Config::getRootSite(), 1, strlen(Config::getRootSite()));

		for ($i=$j=0; $i < length($url) ; $i++) { 
			if ($base == $url[$i]) $guard = true;
			if ($guard) {
				$pars[$j] = $url[$i];
				$j++;
			}
			
		}


		for ($i=0; $i < length($pars)-2 ; $i++) { 
			$result .= "../";
		}

		return $result;

	}







}