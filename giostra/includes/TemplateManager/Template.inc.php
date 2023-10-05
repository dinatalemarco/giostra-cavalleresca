<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package TemplateManger
 * @category Template Engine
 */

require_once(realpath(dirname(__FILE__)).'/path.inc.php');
require_once(realpath(dirname(__FILE__)).'/libs/Smarty.class.php');
require_once(realpath(dirname(__FILE__))."/settings.inc.php");
require_once(realpath(dirname(__FILE__))."/outline.inc.php");		//Setto l'outline della template
require_once(realpath(dirname(__FILE__)).'/fragmentCode.inc.php');	//Setto le porziuoni di template che inietterò nella pagina
require_once(realpath(dirname(__FILE__)).'/javascriptCode.inc.php'); //Setto le porziuoni del generatore di javascript
