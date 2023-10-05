<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Model
 * @category System modeling
 */


$PATH_MODEL = realpath(dirname(__FILE__));

// Metodo per la restituzione dei messaggi 
require_once $PATH_MODEL .'/Result.php';

require_once $PATH_MODEL .'/Type.php';
require_once $PATH_MODEL .'/Struct.php';
require_once $PATH_MODEL .'/Entity.php';
require_once $PATH_MODEL .'/ForeignKey.php';
require_once $PATH_MODEL .'/Table.php';
require_once $PATH_MODEL .'/Form.php';


require_once $PATH_MODEL .'/Model.php';
require_once $PATH_MODEL .'/ViewManagement.php';
require_once $PATH_MODEL .'/FunctionDB.php';
require_once $PATH_MODEL .'/FileManagement.php';
require_once $PATH_MODEL .'/SystemPage.php';



