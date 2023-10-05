<?php
/**
 * Uuoden Php Web framework
 *
 * @author Di Natale Marco <dinatalemarco90@gmail.com>
 * @package  Object
 */

class Roles extends Model{

	use FunctionDB;

	public function __construct(){  

		$this->SetModule("Giostra");
		$this->SetNameService('Roles');

		$this->NewTable("roles");
		$this->NewEntity("id", INTEGER, array(NOT_NULL => true, AUTO_INCREMENT => true));
		$this->NewEntity("name", VARCHAR, array(NOT_NULL => true, length => 255));
		$this->NewEntity("code", INTEGER, array(NOT_NULL => true, length => 11));
		$this->PrimaryKey(array("id"));


	}

}