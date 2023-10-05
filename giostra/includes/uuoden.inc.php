<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  includes
 * @version 1.0.1
 * @see https://github.com/dinatalemarco/Uuoden
 * @license MIT license
 */


$REAL_PATH = realpath(dirname(__FILE__));

require_once($REAL_PATH.'/config.inc.php');
require_once($REAL_PATH.'/Slim/vendor/autoload.php');
require_once($REAL_PATH.'/utility.inc.php');
require_once($REAL_PATH.'/Router/AltoRouter.php');
require_once($REAL_PATH.'/Monolog/Log.php');
require_once($REAL_PATH.'/TemplateManager/Template.inc.php');
require_once($REAL_PATH.'/Email/Email.inc.php');
require_once($REAL_PATH.'/../configuration.php'); 
require_once($REAL_PATH.'/Language/emtiPHP-lang-'.Config::getLanguage().'.php');
require_once($REAL_PATH.'/Security/Security.inc.php');
require_once($REAL_PATH."/Model/model.inc.php");



// Includiamo tutti gli oggetti presenti nella cartella modules
$PathIncObj = $REAL_PATH."/../modules";
$ListIncObj = ListFileDirectory($PathIncObj);

for ($i=0; $i < length($ListIncObj); $i++) 
	if ($ListIncObj[$i] != 'ETSystem' && file_exists($PathIncObj."/".$ListIncObj[$i]."/model/model.inc.php")) 
		require_once($PathIncObj."/".$ListIncObj[$i]."/model/model.inc.php");




/*

// autoload function
function __autoload($class) {

   $REAL_PATH = realpath(dirname(__FILE__));

   $map = array('Uuoden\\Model\\Entity' => $REAL_PATH . '/Model/Entity.php',
		        'Uuoden\\Model\\FileManagement' => $REAL_PATH . '/Model/FileManagement.php',
		        'Uuoden\\Model\\ForeignKey' => $REAL_PATH . '/Model/ForeignKey.php',
		        'Uuoden\\Model\\Form' => $REAL_PATH . '/Model/Form.php',
		        'Uuoden\\Model\\FunctionDB' => $REAL_PATH . '/Model/FunctionDB.php',
		  		'Uuoden\\Model\\Result' => $REAL_PATH . '/Model/Result.php',
		  		'Uuoden\\Model\\Struct' => $REAL_PATH . '/Model/Struct.php',
		  		'Uuoden\\Model\\SystemPage' => $REAL_PATH . '/Model/SystemPage.php',
		  		'Uuoden\\Model\\Table' => $REAL_PATH . '/Model/Table.php',
		  		'Uuoden\\Model\\Type' => $REAL_PATH . '/Model/Type.php',
		  		'Uuoden\\Model\\ViewManagement' => $REAL_PATH . '/Model/ViewManagement.php',
		  		'Uuoden\\Model' => $REAL_PATH . '/Model/Model.php');
    
   	if (isset($map[$class]) && file_exists($map[$class])) 
   		require_once($map[$class]);
   	else "Classe non esitente";


}

*/




